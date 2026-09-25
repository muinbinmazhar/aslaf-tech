<?php

namespace Tests\Feature;

use App\Models\App;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_a_youtube_link_and_public_page_embeds_it(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.apps.store'), [
            'name' => 'Video App',
            'slug' => 'video-app',
            'description' => 'Video app description.',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'is_published' => '1',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.apps.index'));

        $app = App::where('slug', 'video-app')->firstOrFail();
        $this->assertSame('https://youtu.be/dQw4w9WgXcQ', $app->youtube_url);
        $this->get(route('apps.show', $app->slug))
            ->assertOk()
            ->assertSee('youtube-nocookie.com/embed/dQw4w9WgXcQ')
            ->assertSeeInOrder(['Video App', 'detail-media', 'Video app description.']);
    }

    public function test_invalid_youtube_host_is_rejected_and_video_is_optional(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.apps.store'), [
            'name' => 'Bad Video App',
            'slug' => 'bad-video-app',
            'youtube_url' => 'https://youtube.com.evil.example/watch?v=dQw4w9WgXcQ',
        ])->assertSessionHasErrors('youtube_url');

        $app = App::create(['name' => 'No Video App', 'slug' => 'no-video-app', 'is_published' => true]);
        $this->get(route('apps.show', $app->slug))
            ->assertOk()
            ->assertDontSee('youtube-nocookie.com/embed/');
    }

    public function test_admin_can_replace_and_remove_a_video_link(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $app = App::create([
            'name' => 'Editable App',
            'slug' => 'editable-app',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'is_published' => true,
        ]);

        $this->actingAs($admin)->put(route('admin.apps.update', $app), [
            'name' => $app->name,
            'slug' => $app->slug,
            'youtube_url' => '',
            'is_published' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertNull($app->fresh()->youtube_url);
        $this->get(route('apps.show', $app->slug))->assertDontSee('youtube-nocookie.com/embed/');
    }
}
