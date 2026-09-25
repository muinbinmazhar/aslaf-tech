<?php

namespace App\Actions;

use App\Models\App;
use App\Support\AppMedia;
use Throwable;

class UpdateApp
{
    public function __construct(private AppMedia $media) {}

    public function handle(App $app, array $data): void
    {
        $thumbnail = $data['logo'] ?? null;
        $removeThumbnail = (bool) ($data['remove_thumbnail'] ?? false);
        unset($data['logo'], $data['remove_thumbnail']);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $data['is_published'] ?? false;
        $oldPath = $app->logo_path;
        $newPath = null;

        if ($thumbnail !== null) {
            $newPath = $this->media->storeThumbnail($thumbnail);
            $data['logo_path'] = $newPath;
        } elseif ($removeThumbnail) {
            $data['logo_path'] = null;
        }

        try {
            $app->update($data);
        } catch (Throwable $exception) {
            if ($newPath !== null) {
                $this->media->deleteIfUnreferenced($newPath);
            }

            throw $exception;
        }

        if ($oldPath !== null && ($newPath !== null || $removeThumbnail)) {
            $this->media->deleteIfUnreferenced($oldPath);
        }
    }
}
