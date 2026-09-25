<?php

namespace App\Actions;

use App\Models\App;
use App\Models\AppScreenshot;
use App\Support\AppMedia;
use Illuminate\Support\Facades\DB;

class DeleteAppScreenshot
{
    public function __construct(private AppMedia $media) {}

    public function handle(App $app, AppScreenshot $screenshot): void
    {
        $path = DB::transaction(function () use ($app, $screenshot): string {
            App::query()->whereKey($app->id)->lockForUpdate()->firstOrFail();
            $owned = $app->screenshots()->whereKey($screenshot->id)->firstOrFail();
            $path = $owned->file_path;
            $owned->delete();
            foreach ($app->screenshots()->get() as $index => $remaining) {
                $remaining->update(['sort_order' => $index + 1]);
            }
            return $path;
        });

        $this->media->deleteIfUnreferenced($path);
    }
}
