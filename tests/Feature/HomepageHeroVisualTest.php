<?php

namespace Tests\Feature;

use App\Models\HomepageContent;
use App\Models\User;
use App\Support\WebsiteMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class HomepageHeroVisualTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_admin_form_shows_visual_controls_and_empty_preview(): void
    {
        $this->actingAsAdmin();
        $this->get(route('admin.website.homepage.edit'))->assertOk()
            ->assertSee('Hero Visual')->assertSee('Hero Image / Logo')
            ->assertSee('Visual Alt Text')->assertSee('Floating Label 1')->assertSee('Floating Label 2')
            ->assertSee('Show Decorative Rings')->assertSee('Show Floating Labels')
            ->assertSee('No hero visual uploaded.')->assertSee('multipart/form-data');
    }

    public function test_png_upload_renders_image_alt_labels_and_existing_hero_copy(): void
    {
        $this->actingAsAdmin();
        $data = $this->payload();
        $data['hero_visual'] = UploadedFile::fake()->image('brand.png', 500, 200);
        $data['content']['hero']['visual_alt'] = 'Brand & Development';
        $data['content']['hero']['visual_label_one'] = 'Create';
        $data['content']['hero']['visual_label_two'] = 'Deliver';
        $this->put(route('admin.website.homepage.update'), $data)->assertSessionHasNoErrors()->assertRedirect();
        $hero = $this->hero();
        Storage::disk('public')->assertExists($hero['visual_path']);
        $this->assertStringStartsWith('homepage/hero/', $hero['visual_path']);
        $this->assertSame('Brand & Development', $hero['visual_alt']);
        $this->assertSame('Create', $hero['visual_label_one']);
        $this->assertSame('Deliver', $hero['visual_label_two']);
        $this->assertTrue($hero['visual_show_rings']);
        $this->assertTrue($hero['visual_show_labels']);
        $this->get(route('home'))->assertOk()->assertSee(asset('storage/'.$hero['visual_path']), false)
            ->assertSee('alt="Brand &amp; Development"', false)->assertSee('Create')->assertSee('Deliver')
            ->assertSee('class="visual-orbit orbit-one"', false)->assertSee($hero['heading']);
        $this->get(route('admin.website.homepage.edit'))->assertOk()
            ->assertSee(asset('storage/'.$hero['visual_path']), false)->assertSee('Remove Image');
    }

    public function test_jpeg_and_webp_uploads_are_supported(): void
    {
        $this->actingAsAdmin();
        foreach (['jpg', 'webp'] as $extension) {
            $this->put(route('admin.website.homepage.update'), $this->payload([
                'hero_visual' => UploadedFile::fake()->image('brand.'.$extension),
            ]))->assertSessionHasNoErrors();
            Storage::disk('public')->assertExists($this->hero()['visual_path']);
        }
    }

    public function test_invalid_svg_oversized_images_and_html_are_rejected_without_losing_image(): void
    {
        $this->actingAsAdmin();
        $path = $this->upload();
        foreach ([
            UploadedFile::fake()->create('document.txt', 1, 'text/plain'),
            UploadedFile::fake()->createWithContent('brand.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>'),
            UploadedFile::fake()->image('large.png')->size(5121),
        ] as $file) {
            $this->put(route('admin.website.homepage.update'), $this->payload(['hero_visual' => $file]))
                ->assertSessionHasErrors('hero_visual');
            $this->assertSame($path, $this->hero()['visual_path']);
            Storage::disk('public')->assertExists($path);
        }
        foreach (['visual_alt', 'visual_label_one', 'visual_label_two'] as $field) {
            $data = $this->payload();
            $data['content']['hero'][$field] = '<script>alert(1)</script>';
            $this->put(route('admin.website.homepage.update'), $data)->assertSessionHasErrors('content.hero.'.$field);
        }
    }

    public function test_replace_and_remove_clean_only_the_previous_managed_image(): void
    {
        $this->actingAsAdmin();
        $old = $this->upload();
        $new = $this->upload();
        $this->assertNotSame($old, $new);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($new);
        $this->put(route('admin.website.homepage.update'), $this->payload(['remove_hero_visual' => 1]))
            ->assertSessionHasNoErrors();
        $this->assertNull($this->hero()['visual_path']);
        Storage::disk('public')->assertMissing($new);
        $this->get(route('home'))->assertOk()->assertDontSee('class="visual-logo"', false)
            ->assertDontSee('class="visual-orbit orbit-one"', false)
            ->assertSee(HomepageContent::defaults()['hero']['heading']);
    }

    public function test_arbitrary_paths_cannot_be_set_or_deleted(): void
    {
        $this->actingAsAdmin();
        Storage::disk('public')->put('website-logos/keep.png', 'keep');
        HomepageContent::create(['section' => 'hero', 'content' => ['visual_path' => 'website-logos/keep.png']]);
        $data = $this->payload(['remove_hero_visual' => 1]);
        $data['content']['hero']['visual_path'] = '../private.png';
        $this->put(route('admin.website.homepage.update'), $data)->assertSessionHasNoErrors();
        Storage::disk('public')->assertExists('website-logos/keep.png');
        $this->assertNull($this->hero()['visual_path']);
    }

    public function test_missing_checkboxes_save_false_and_metadata_updates_preserve_image(): void
    {
        $this->actingAsAdmin();
        $path = $this->upload();
        $data = $this->payload();
        unset($data['content']['hero']['visual_show_rings'], $data['content']['hero']['visual_show_labels']);
        $data['content']['hero']['visual_alt'] = 'Updated description';
        $this->put(route('admin.website.homepage.update'), $data)->assertSessionHasNoErrors();
        $this->assertSame($path, $this->hero()['visual_path']);
        $this->assertFalse($this->hero()['visual_show_rings']);
        $this->assertFalse($this->hero()['visual_show_labels']);
        $this->get(route('home'))->assertSee('Updated description')
            ->assertDontSee('class="visual-orbit orbit-one"', false)->assertDontSee('class="visual-chip chip-one"', false);
        $this->get(route('admin.website.homepage.edit'))->assertOk()
            ->assertDontSee('value="1" checked', false);
    }

    public function test_empty_individual_label_is_hidden_and_toggles_are_independent(): void
    {
        $this->actingAsAdmin();
        $this->upload();
        $data = $this->payload();
        $data['content']['hero']['visual_label_two'] = '';
        $data['content']['hero']['visual_show_rings'] = 0;
        $this->put(route('admin.website.homepage.update'), $data)->assertSessionHasNoErrors();
        $this->get(route('home'))->assertSee('class="visual-chip chip-one"', false)
            ->assertDontSee('class="visual-chip chip-two"', false)->assertDontSee('class="visual-orbit orbit-one"', false);
        $data['content']['hero']['visual_show_rings'] = 1;
        $data['content']['hero']['visual_show_labels'] = 0;
        $this->put(route('admin.website.homepage.update'), $data)->assertSessionHasNoErrors();
        $this->get(route('home'))->assertSee('class="visual-orbit orbit-one"', false)
            ->assertDontSee('class="visual-chip chip-one"', false);
    }

    public function test_absent_or_missing_image_does_not_render_broken_visual(): void
    {
        $this->get(route('home'))->assertOk()->assertDontSee('class="visual-logo"', false);
        HomepageContent::create(['section' => 'hero', 'content' => ['visual_path' => 'homepage/hero/missing.png']]);
        $this->get(route('home'))->assertOk()->assertDontSee('class="visual-logo"', false);
        $this->actingAsAdmin();
        $this->get(route('admin.website.homepage.edit'))->assertSee('No hero visual uploaded.')
            ->assertDontSee('src=""', false);
    }

    public function test_failed_upload_preserves_existing_image(): void
    {
        $this->actingAsAdmin();
        $path = $this->upload();
        $this->mock(WebsiteMedia::class, function ($mock): void {
            $mock->shouldReceive('store')->once()->andThrow(ValidationException::withMessages(['hero_visual' => 'Storage failed.']));
        });
        $this->put(route('admin.website.homepage.update'), $this->payload([
            'hero_visual' => UploadedFile::fake()->image('replacement.png'),
        ]))->assertSessionHasErrors('hero_visual');
        $this->assertSame($path, $this->hero()['visual_path']);
        Storage::disk('public')->assertExists($path);
    }

    public function test_failed_database_save_cleans_new_upload_and_keeps_old_image(): void
    {
        $this->actingAsAdmin();
        $path = $this->upload();
        HomepageContent::saving(function (): void {
            throw new \RuntimeException('Database failed.');
        });
        try {
            $this->put(route('admin.website.homepage.update'), $this->payload([
                'hero_visual' => UploadedFile::fake()->image('replacement.png'),
            ]))->assertStatus(500);
        } finally {
            HomepageContent::flushEventListeners();
        }
        $this->assertSame($path, $this->hero()['visual_path']);
        $this->assertSame([$path], Storage::disk('public')->allFiles('homepage/hero'));
    }

    public function test_validation_errors_preserve_entered_visual_fields_and_toggles(): void
    {
        $this->actingAsAdmin();
        $path = $this->upload();
        $data = $this->payload(['hero_visual' => UploadedFile::fake()->create('bad.txt')]);
        $data['content']['hero']['visual_alt'] = 'Keep my alt';
        $data['content']['hero']['visual_label_one'] = 'Keep first';
        $data['content']['hero']['visual_label_two'] = 'Keep second';
        $data['content']['hero']['visual_show_rings'] = 0;
        $data['content']['hero']['visual_show_labels'] = 0;
        $this->from(route('admin.website.homepage.edit'))->put(route('admin.website.homepage.update'), $data)
            ->assertSessionHasErrors('hero_visual')
            ->assertSessionHasInput('content.hero.visual_alt', 'Keep my alt')
            ->assertSessionHasInput('content.hero.visual_show_rings', 0)
            ->assertSessionHasInput('content.hero.visual_show_labels', 0);
        $this->get(route('admin.website.homepage.edit'))->assertSee('Keep my alt')
            ->assertSee('Keep first')->assertSee('Keep second')->assertDontSee('value="1" checked', false);
        $this->assertSame($path, $this->hero()['visual_path']);
    }

    private function actingAsAdmin(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }

    private function payload(array $extra = []): array
    {
        return array_replace(['content' => HomepageContent::defaults()], $extra);
    }

    private function hero(): array
    {
        return HomepageContent::query()->where('section', 'hero')->firstOrFail()->content;
    }

    private function upload(): string
    {
        $this->put(route('admin.website.homepage.update'), $this->payload([
            'hero_visual' => UploadedFile::fake()->image('hero.png'),
        ]))->assertSessionHasNoErrors();

        return $this->hero()['visual_path'];
    }
}
