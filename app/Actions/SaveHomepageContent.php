<?php

namespace App\Actions;

use App\Models\HomepageContent;
use App\Support\WebsiteMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

class SaveHomepageContent
{
    public function __construct(private WebsiteMedia $media) {}

    public function handle(array $sections, ?UploadedFile $heroVisual = null, bool $removeHeroVisual = false): void
    {
        $newPath = $heroVisual ? $this->media->store($heroVisual, 'homepage/hero', 'hero_visual') : null;
        $oldPath = null;

        try {
            DB::transaction(function () use ($sections, $newPath, $removeHeroVisual, &$oldPath) {
                foreach (HomepageContent::defaults() as $section => $fields) {
                    $record = HomepageContent::query()->where('section', $section)->lockForUpdate()->first();
                    $content = array_replace($fields, $record?->content ?? [], $sections[$section] ?? []);
                    if ($section === 'hero') {
                        $oldPath = $record?->content['visual_path'] ?? null;
                        $content['visual_path'] = $newPath ?? ($removeHeroVisual ? null : $oldPath);
                        foreach (['visual_show_rings', 'visual_show_labels'] as $field) {
                            $content[$field] = (bool) $content[$field];
                        }
                    }
                    HomepageContent::query()->updateOrCreate(['section' => $section], ['content' => $content]);
                }
            });
        } catch (Throwable $exception) {
            $this->media->deleteHeroVisual($newPath);
            throw $exception;
        }

        if ($newPath !== null || $removeHeroVisual) {
            $this->media->deleteHeroVisual($oldPath);
        }
    }
}
