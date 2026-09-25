<?php

namespace Tests\Feature;

use App\Models\App;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_and_apps_page_load(): void
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('apps.index'))->assertOk();
    }

    public function test_only_published_apps_appear_on_public_pages_and_menu(): void
    {
        $published = $this->app('Published Product', 'published-product', true);
        $draft = $this->app('Private Draft Product', 'private-draft-product', false);

        $this->get(route('home'))
            ->assertSee($published->name)
            ->assertDontSee($draft->name);
        $this->get(route('apps.index'))
            ->assertSee($published->name)
            ->assertDontSee($draft->name);
        $this->get(route('apps.show', $published->slug))->assertOk()->assertSee($published->name);
        $this->get(route('apps.show', $draft->slug))->assertNotFound();
        $this->get(route('apps.show', 'missing-app'))->assertNotFound();
    }

    public function test_apps_pagination_never_exposes_drafts(): void
    {
        foreach (range(1, 13) as $number) {
            $this->app("Published Product {$number}", "published-product-{$number}", true);
        }
        $this->app('Private Draft Product', 'private-draft-product', false);

        $this->get(route('apps.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Published Product 1')
            ->assertDontSee('Private Draft Product');
    }

    public function test_existing_admin_authentication_and_apps_routes_remain_available(): void
    {
        foreach (['admin.login', 'admin.logout', 'admin.dashboard', 'admin.apps.index', 'admin.apps.create', 'admin.apps.store', 'admin.apps.edit', 'admin.apps.update', 'admin.apps.destroy'] as $name) {
            $this->assertTrue(Route::has($name));
        }

        $this->get(route('admin.apps.index'))->assertRedirect(route('admin.login'));

        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin)->get(route('admin.apps.index'))->assertOk();
    }

    private function app(string $name, string $slug, bool $published): App
    {
        return App::create([
            'name' => $name,
            'slug' => $slug,
            'is_published' => $published,
        ]);
    }
}
