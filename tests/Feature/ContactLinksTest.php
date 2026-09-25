<?php

namespace Tests\Feature;

use App\Actions\ImportLegacyContacts;
use App\Models\App;
use App\Models\ContactLink;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Support\ContactLinkResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_management_requires_an_admin(): void
    {
        $this->get(route('admin.contact-links.index'))->assertRedirect(route('admin.login'));
        $this->post(route('admin.contact-links.store'), $this->payload())->assertRedirect(route('admin.login'));
        $this->postJson(route('admin.contact-links.reorder'), ['page' => 1, 'ids' => [1]])->assertUnauthorized();

        $this->actingAs(User::factory()->create());
        $this->get(route('admin.contact-links.index'))->assertForbidden();
        $this->post(route('admin.contact-links.store'), $this->payload())->assertForbidden();
        $this->postJson(route('admin.contact-links.reorder'), ['page' => 1, 'ids' => [1]])->assertForbidden();
    }

    public function test_admin_can_create_same_type_twice_edit_toggle_and_delete(): void
    {
        $this->admin();
        $this->post(route('admin.contact-links.store'), $this->payload())->assertRedirect(route('admin.contact-links.index'));
        $this->post(route('admin.contact-links.store'), $this->payload(['display_name' => 'Second Support']))->assertRedirect();
        $this->assertSame(2, ContactLink::where('type', 'whatsapp')->count());

        $link = ContactLink::firstOrFail();
        $this->get(route('admin.contact-links.index'))->assertOk()->assertSee('WhatsApp Support');
        $this->get(route('admin.contact-links.edit', $link))->assertOk()->assertSee('WhatsApp Support');
        $this->put(route('admin.contact-links.update', $link), $this->payload(['display_name' => 'Sales Support']))->assertRedirect();
        $this->assertSame('Sales Support', $link->fresh()->display_name);
        $this->post(route('admin.contact-links.toggle', $link))->assertRedirect();
        $this->assertFalse($link->fresh()->is_visible);
        $this->delete(route('admin.contact-links.destroy', $link))->assertRedirect();
        $this->assertDatabaseMissing('contact_links', ['id' => $link->id]);
    }

    public function test_invalid_values_and_missing_locations_are_rejected(): void
    {
        $this->admin();
        foreach ([
            $this->payload(['value' => '123']),
            $this->payload(['type' => 'mobile', 'value' => 'abc']),
            $this->payload(['type' => 'email', 'value' => 'wrong']),
            $this->payload(['type' => 'custom', 'value' => 'javascript:alert(1)']),
            $this->payload(['type' => 'youtube', 'value' => 'https://evil.example/video']),
            $this->payload(['type' => 'google_maps', 'value' => 'https://example.com/maps']),
        ] as $payload) {
            $this->post(route('admin.contact-links.store'), $payload)->assertSessionHasErrors('value');
        }
        $this->post(route('admin.contact-links.store'), $this->payload([
            'show_on_homepage' => 0, 'show_in_footer' => 0, 'show_in_header' => 0,
        ]))->assertSessionHasErrors('show_on_homepage');
        $this->post(route('admin.contact-links.store'), $this->payload([
            'type' => 'address', 'value' => 'A real office address', 'link_url' => 'https://example.com/maps',
        ]))->assertSessionHasErrors('link_url');
        $this->assertSame(0, ContactLink::count());
    }

    public function test_whatsapp_is_normalized_and_public_locations_respect_visibility(): void
    {
        $this->admin();
        $this->post(route('admin.contact-links.store'), $this->payload(['show_in_header' => 1]))->assertRedirect();
        $link = ContactLink::firstOrFail();
        $this->assertSame('https://wa.me/8801712345678', app(ContactLinkResolver::class)->url($link));
        $this->get(route('home'))->assertOk()->assertSee('WhatsApp Support')->assertSee('Quick Contact')->assertSee('id="site-nav"', false);
        $this->get(route('apps.index'))->assertOk()->assertSee('Quick Contact');

        $link->update(['show_on_homepage' => false, 'show_in_footer' => true, 'show_in_header' => false]);
        $this->get(route('home'))->assertDontSee('contact-card-type')->assertSee('WhatsApp Support');
        $link->update(['show_on_homepage' => true, 'show_in_footer' => false]);
        $this->get(route('home'))->assertSee('contact-card-type')->assertDontSee('footer-contact');
        $link->update(['is_visible' => false]);
        $this->get(route('home'))->assertDontSee('WhatsApp Support');
    }

    public function test_legacy_import_is_idempotent_and_existing_features_still_load(): void
    {
        WebsiteSetting::create(['website_name' => 'ASLAF TECH', 'contact_email' => 'hello@example.test']);
        $import = app(ImportLegacyContacts::class);
        $this->assertSame(1, $import->handle());
        $this->assertSame(0, $import->handle());
        $this->assertSame(1, ContactLink::count());
        $this->get(route('home'))->assertSee('hello@example.test');

        $this->post(route('contact.store'), [
            'name' => 'Jane Doe', 'email' => 'jane@example.test', 'subject' => 'Hello',
            'message' => 'Please tell me about your services and contact details.',
        ])->assertRedirect(route('home').'#contact');
        $app = App::create(['name' => 'Existing App', 'slug' => 'existing-app', 'is_published' => true]);
        $this->get(route('apps.show', $app->slug))->assertOk();
        $this->admin();
        $this->get(route('admin.apps.index'))->assertOk();
        $this->get(route('admin.messages.index'))->assertOk();
    }

    public function test_admin_can_reorder_contacts_and_public_uses_the_saved_order(): void
    {
        $this->admin();
        foreach (['First', 'Second', 'Third'] as $name) {
            $this->post(route('admin.contact-links.store'), $this->payload([
                'display_name' => $name, 'sort_order' => 999,
            ]))->assertRedirect();
        }
        $links = ContactLink::orderBy('id')->get();
        $this->assertSame([0, 1, 2], $links->pluck('sort_order')->all());

        $ids = [$links[2]->id, $links[0]->id, $links[1]->id];
        $this->postJson(route('admin.contact-links.reorder'), ['page' => 1, 'ids' => $ids])->assertOk();
        $this->assertSame($ids, ContactLink::orderBy('sort_order')->pluck('id')->all());
        $this->get(route('home'))->assertSeeInOrder(['Third', 'First', 'Second']);

        $this->postJson(route('admin.contact-links.reorder'), ['page' => 1, 'ids' => [$links[0]->id]])
            ->assertUnprocessable();
        $this->assertSame($ids, ContactLink::orderBy('sort_order')->pluck('id')->all());
        $this->get(route('admin.contact-links.index', ['q' => 'First']))
            ->assertOk()->assertSee('Clear search and filters to change contact order.')
            ->assertDontSee('data-reorder-url', false);
    }

    public function test_move_controls_cross_pagination_boundaries(): void
    {
        $this->admin();
        $links = collect(range(1, 17))->map(fn ($number) => ContactLink::create([
            'type' => 'email', 'display_name' => 'Contact '.$number,
            'value' => 'contact'.$number.'@example.test', 'sort_order' => $number,
            'show_in_footer' => true,
        ]));

        $this->post(route('admin.contact-links.move', $links[15]), ['direction' => 'up'])->assertRedirect();
        $this->assertSame($links[15]->id, ContactLink::orderBy('sort_order')->skip(14)->value('id'));

        $pageTwo = ContactLink::orderBy('sort_order')->orderBy('id')->skip(15)->take(2)->pluck('id')->all();
        $this->postJson(route('admin.contact-links.reorder'), [
            'page' => 2, 'ids' => array_reverse($pageTwo),
        ])->assertOk();
        $this->assertSame(array_reverse($pageTwo), ContactLink::orderBy('sort_order')->skip(15)->take(2)->pluck('id')->all());
        $this->post(route('admin.contact-links.move', $links[0]), ['direction' => 'sideways'])
            ->assertSessionHasErrors('direction');
    }

    private function admin(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'type' => 'whatsapp', 'display_name' => 'WhatsApp Support', 'value' => '01712345678',
            'show_on_homepage' => 1, 'show_in_footer' => 0, 'show_in_header' => 0,
            'is_visible' => 1, 'sort_order' => 0,
        ], $overrides);
    }
}
