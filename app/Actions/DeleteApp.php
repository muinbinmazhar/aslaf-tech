<?php

namespace App\Actions;

use App\Models\App;
use App\Support\AppMedia;

class DeleteApp
{
    public function __construct(private AppMedia $media) {}

    public function handle(App $app): void
    {
        $paths = $app->screenshots()->pluck('file_path')->all();
        $paths[] = $app->logo_path;

        $app->delete();

        foreach (array_unique($paths) as $path) {
            $this->media->deleteIfUnreferenced($path);
        }
    }
}
