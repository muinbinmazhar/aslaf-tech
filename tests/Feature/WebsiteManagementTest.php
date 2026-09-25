<?php

namespace Tests\Feature;

use App\Models\App;
use App\Models\ContactMessage;
use App\Models\HomepageContent;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\User;
use App\Models\WebsiteSetting;
use Database\Seeders\WebsiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WebsiteManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_open_website_management(): void
    {
        foreach (['admin.website.settings.edit', 'admin.website.homepage.edit', 'admin.services.index', 'admin.portfolio.index', 'admin.messages.index'] as $route) {
            $this->get(route($route))->assertRedirect(route('admin.login'));
        }
    }

    public function test_admin_can_save_settings_and_homepage_content(): void
    {
        $this->actingAsAdmin();
        $this->put(route('admin.website.settings.update'), ['website_name' => 'ASLAF Studio', 'contact_email' => 'hello@example.test'])
            ->assertRedirect();
        $this->assertDatabaseHas('website_settings', ['website_name' => 'ASLAF Studio', 'contact_email' => 'hello@example.test']);

        $content = HomepageContent::defaults();
        $content['hero']['heading'] = 'Made for the web';
        $this->put(route('admin.website.homepage.update'), ['content' => $content])->assertRedirect();
        $this->get(route('home'))->assertOk()->assertSee('Made for the web');
    }

    public function test_services_and_portfolio_only_show_published_records(): void
    {
        Service::create(['title' => 'Visible Service', 'short_description' => 'Shown on the site', 'icon' => 'code', 'is_published' => true]);
        Service::create(['title' => 'Hidden Service', 'short_description' => 'Not shown', 'icon' => 'code', 'is_published' => false]);
        PortfolioProject::create(['title' => 'Visible Project', 'slug' => 'visible-project', 'is_published' => true]);
        PortfolioProject::create(['title' => 'Hidden Project', 'slug' => 'hidden-project', 'is_published' => false]);

        $this->get(route('home'))->assertSee('Visible Service')->assertDontSee('Hidden Service')
            ->assertSee('Visible Project')->assertDontSee('Hidden Project');
        $this->get(route('portfolio.show', 'visible-project'))->assertOk();
        $this->get(route('portfolio.show', 'hidden-project'))->assertNotFound();
    }

    public function test_admin_can_set_and_clear_an_optional_service_card_label(): void
    {
        $this->actingAsAdmin();
        $data = [
            'title' => 'Product Design', 'short_description' => 'Design for digital products.',
            'icon' => 'design', 'sort_order' => 0, 'is_published' => 1,
            'badge_text' => ' Coming Soon ',
        ];

        $this->post(route('admin.services.store'), $data)->assertRedirect(route('admin.services.index'));
        $service = Service::firstOrFail();
        $this->assertSame('Coming Soon', $service->badge_text);
        $this->get(route('home'))->assertSee('Coming Soon');

        $this->put(route('admin.services.update', $service), array_replace($data, ['badge_text' => '']))->assertRedirect();
        $this->assertNull($service->fresh()->badge_text);
        $this->get(route('home'))->assertDontSee('Coming Soon');

        $this->put(route('admin.services.update', $service), array_replace($data, ['badge_text' => str_repeat('x', 41)]))
            ->assertSessionHasErrors('badge_text');

        $this->put(route('admin.services.update', $service), array_replace($data, [
            'badge_text' => '', 'is_published' => 0,
        ]))->assertRedirect();
        $this->assertFalse($service->fresh()->is_published);
        $this->get(route('home'))->assertDontSee('Product Design');
    }

    public function test_contact_form_validates_and_saves_without_ip_address(): void
    {
        $this->post(route('contact.store'), ['name' => '', 'email' => 'invalid', 'subject' => '', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $data = ['name' => 'Jane Doe', 'email' => 'jane@example.test', 'subject' => 'A project', 'message' => 'Please tell me about website development.'];
        $this->post(route('contact.store'), $data)->assertRedirect(route('home').'#contact');
        $this->assertDatabaseHas('contact_messages', ['email' => 'jane@example.test', 'subject' => 'A project']);
        $this->assertEquals(1, ContactMessage::count());
    }

    public function test_admin_can_view_and_change_message_read_state(): void
    {
        $message = ContactMessage::create(['name' => 'Jane Doe', 'email' => 'jane@example.test', 'subject' => 'Hello', 'message' => 'A message for your team.']);
        $this->actingAsAdmin();
        $this->get(route('admin.messages.show', $message))->assertOk()->assertSee('Hello');
        $this->post(route('admin.messages.toggle', $message))->assertRedirect();
        $this->assertTrue($message->fresh()->is_read);
        $this->post(route('admin.messages.toggle', $message))->assertRedirect();
        $this->assertFalse($message->fresh()->is_read);
    }

    public function test_website_logo_replacement_and_removal_clean_up_only_managed_files(): void
    {
        Storage::fake('public');
        $this->actingAsAdmin();

        $this->put(route('admin.website.settings.update'), [
            'website_name' => 'ASLAF TECH',
            'logo' => UploadedFile::fake()->image('first.png'),
        ])->assertRedirect();
        $oldPath = WebsiteSetting::firstOrFail()->logo_path;
        Storage::disk('public')->assertExists($oldPath);

        $this->put(route('admin.website.settings.update'), [
            'website_name' => 'ASLAF TECH',
            'logo' => UploadedFile::fake()->image('second.png'),
        ])->assertRedirect();
        $newPath = WebsiteSetting::firstOrFail()->logo_path;
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);

        $this->put(route('admin.website.settings.update'), [
            'website_name' => 'ASLAF TECH', 'remove_logo' => '1',
        ])->assertRedirect();
        $this->assertNull(WebsiteSetting::firstOrFail()->logo_path);
        Storage::disk('public')->assertMissing($newPath);
    }

    public function test_portfolio_thumbnail_is_removed_after_project_deletion(): void
    {
        Storage::fake('public');
        $this->actingAsAdmin();
        $this->post(route('admin.portfolio.store'), [
            'title' => 'Real Project', 'slug' => 'real-project',
            'sort_order' => 0, 'is_published' => 1,
            'thumbnail' => UploadedFile::fake()->image('project.png'),
        ])->assertRedirect(route('admin.portfolio.index'));

        $project = PortfolioProject::firstOrFail();
        $path = $project->thumbnail_path;
        Storage::disk('public')->assertExists($path);
        $this->delete(route('admin.portfolio.destroy', $project))->assertRedirect();
        Storage::disk('public')->assertMissing($path);
    }

    public function test_default_seeder_is_idempotent_and_existing_app_routes_work(): void
    {
        $this->seed(WebsiteContentSeeder::class);
        $this->seed(WebsiteContentSeeder::class);
        $this->assertEquals(6, Service::count());
        $this->assertEquals(6, HomepageContent::count());
        $this->assertEquals(1, WebsiteSetting::count());

        $app = App::create(['name' => 'Existing App', 'slug' => 'existing-app', 'is_published' => true]);
        $this->get(route('apps.show', $app->slug))->assertOk();
        $this->assertTrue(Route::has('admin.apps.index'));
        $this->assertTrue(Route::has('admin.apps.store'));
        $this->actingAsAdmin()->get(route('admin.apps.index'))->assertOk();
    }

    private function actingAsAdmin(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }
}
