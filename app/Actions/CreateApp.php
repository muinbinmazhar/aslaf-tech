<?php

namespace App\Actions;

use App\Models\App;
use App\Support\AppMedia;
use Throwable;

class CreateApp
{
    public function __construct(private AppMedia $media) {}

    public function handle(array $data): App
    {
        $logo = $data['logo'] ?? null;
        unset($data['logo']);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $data['is_published'] ?? false;
        $path = null;

        if ($logo !== null) {
            $path = $this->media->storeThumbnail($logo);
            $data['logo_path'] = $path;
        }

        try {
            return App::create($data);
        } catch (Throwable $exception) {
            if ($path !== null) {
                $this->media->deleteIfUnreferenced($path);
            }

            throw $exception;
        }
    }
}
