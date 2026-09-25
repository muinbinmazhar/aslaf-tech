<?php

namespace Tests\Feature;

use App\Models\App;
use App\Models\User;
use App\Actions\DeleteApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AppScreenshotsTest extends TestCase
{
    use RefreshDatabase;

    private function app(bool $published = true): App
    {
        return App::create(['name' => 'Sample App', 'slug' => 'sample-app', 'is_published' => $published]);
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->forceFill(['is_admin' => true])->save();
        return $user;
    }

    public function test_admin_access_is_protected(): void
    {
        $app = $this->app();
        $this->get(route('admin.apps.screenshots.index', $app))->assertRedirect(route('admin.login'));
        $this->actingAs(User::factory()->create())->get(route('admin.apps.screenshots.index', $app))->assertForbidden();
        $this->actingAs($this->admin())->get(route('admin.apps.screenshots.index', $app))->assertOk();
    }

    public function test_upload_appends_files_and_enforces_limits(): void
    {
        Storage::fake('public');
        $app = $this->app();
        $this->actingAs($this->admin());
        $this->post(route('admin.apps.screenshots.store', $app), ['screenshots' => [UploadedFile::fake()->image('first.jpg', 400, 600), UploadedFile::fake()->image('second.png', 500, 500)]])->assertSessionHasNoErrors();
        $this->assertSame(2, $app->screenshots()->count());
        foreach ($app->screenshots as $screenshot) {
            $this->assertStringStartsWith("app-screenshots/{$app->id}/", $screenshot->file_path);
            Storage::disk('public')->assertExists($screenshot->file_path);
        }
        $this->post(route('admin.apps.screenshots.store', $app), ['screenshots' => [UploadedFile::fake()->image('third.webp', 500, 500)]])->assertSessionHasNoErrors();
        $this->assertSame(3, $app->screenshots()->count());
        $this->post(route('admin.apps.screenshots.store', $app), ['screenshots' => [UploadedFile::fake()->create('bad.pdf', 20, 'application/pdf')]])->assertSessionHasErrors();
        $this->post(route('admin.apps.screenshots.store', $app), ['screenshots' => [UploadedFile::fake()->image('small.jpg', 100, 100)]])->assertSessionHasErrors();
        $this->post(route('admin.apps.screenshots.store', $app), ['screenshots' => [UploadedFile::fake()->image('large.jpg')->size(5121)]])->assertSessionHasErrors();
        $this->post(route('admin.apps.screenshots.store', $app), ['screenshots' => array_map(fn ($n) => UploadedFile::fake()->image("{$n}.jpg", 400, 400), range(1, 11))])->assertSessionHasErrors('screenshots');
        $this->assertSame(3, $app->screenshots()->count());
    }

    public function test_reorder_and_delete_are_scoped_to_app(): void
    {
        Storage::fake('public');
        $app = $this->app();
        $other = App::create(['name' => 'Other', 'slug' => 'other']);
        $first = $app->screenshots()->create(['file_path' => 'app-screenshots/'.$app->id.'/first.jpg', 'sort_order' => 1]);
        $second = $app->screenshots()->create(['file_path' => 'app-screenshots/'.$app->id.'/second.jpg', 'sort_order' => 2]);
        $foreign = $other->screenshots()->create(['file_path' => 'app-screenshots/'.$other->id.'/foreign.jpg', 'sort_order' => 1]);
        Storage::disk('public')->put($first->file_path, 'first');
        Storage::disk('public')->put($second->file_path, 'second');
        Storage::disk('public')->put($foreign->file_path, 'foreign');
        $this->actingAs($this->admin());
        $url = route('admin.apps.screenshots.reorder', $app);
        $this->post($url, ['screenshot_ids' => [$second->id, $first->id]])->assertSessionHasNoErrors();
        $this->assertSame(1, $second->fresh()->sort_order);
        $this->assertSame(2, $first->fresh()->sort_order);
        $this->post($url, ['screenshot_ids' => [$first->id, $foreign->id]])->assertSessionHasErrors();
        $this->post($url, ['screenshot_ids' => [$first->id, $first->id]])->assertSessionHasErrors();
        $this->post($url, ['screenshot_ids' => [$first->id]])->assertSessionHasErrors();
        $this->delete(route('admin.apps.screenshots.destroy', [$app, $foreign]))->assertNotFound();
        $this->delete(route('admin.apps.screenshots.destroy', [$app, $first]))->assertRedirect();
        $this->assertDatabaseMissing('app_screenshots', ['id' => $first->id]);
        Storage::disk('public')->assertMissing($first->file_path);
        Storage::disk('public')->assertExists($second->file_path);
        Storage::disk('public')->assertExists($foreign->file_path);
    }

    public function test_public_gallery_only_shows_published_app_screenshots(): void
    {
        $app = $this->app();
        $app->screenshots()->create(['file_path' => 'app-screenshots/1/later.jpg', 'sort_order' => 2]);
        $app->screenshots()->create(['file_path' => 'app-screenshots/1/first.jpg', 'sort_order' => 1]);
        $response = $this->get(route('apps.show', $app->slug))->assertOk()->assertSee('App Screenshots');
        $this->assertTrue(strpos($response->getContent(), 'first.jpg') < strpos($response->getContent(), 'later.jpg'));
        $app->update(['is_published' => false]);
        $this->get(route('apps.show', $app->slug))->assertNotFound();
        $app->update(['is_published' => true]);
        $app->screenshots()->delete();
        $this->get(route('apps.show', $app->slug))->assertDontSee('App Screenshots');
    }

    public function test_maximum_thirty_screenshots_is_enforced(): void
    {
        Storage::fake('public');
        $app = $this->app();
        foreach (range(1, 30) as $number) {
            $app->screenshots()->create(['file_path' => "app-screenshots/{$app->id}/{$number}.jpg", 'sort_order' => $number]);
        }
        $this->actingAs($this->admin())->post(route('admin.apps.screenshots.store', $app), [
            'screenshots' => [UploadedFile::fake()->image('extra.jpg', 400, 400)],
        ])->assertSessionHasErrors('screenshots');
        $this->assertSame(30, $app->screenshots()->count());
    }

    public function test_deleting_app_cleans_up_its_screenshot_files(): void
    {
        Storage::fake('public');
        $app = $this->app();
        $path = "app-screenshots/{$app->id}/one.jpg";
        $app->screenshots()->create(['file_path' => $path, 'sort_order' => 1]);
        Storage::disk('public')->put($path, 'image');
        app(DeleteApp::class)->handle($app);
        $this->assertDatabaseMissing('app_screenshots', ['file_path' => $path]);
        Storage::disk('public')->assertMissing($path);
    }
}
