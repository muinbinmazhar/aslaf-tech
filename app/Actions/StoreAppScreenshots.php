<?php

namespace App\Actions;

use App\Models\App;
use App\Support\AppMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class StoreAppScreenshots
{
    public function __construct(private AppMedia $media) {}

    public function handle(App $app, array $files): void
    {
        $stored = [];

        try {
            DB::transaction(function () use ($app, $files, &$stored): void {
                App::query()->whereKey($app->id)->lockForUpdate()->firstOrFail();
                $count = $app->screenshots()->count();
                if ($count + count($files) > 30) {
                    throw ValidationException::withMessages(['screenshots' => 'An app can have up to 30 screenshots.']);
                }

                $order = (int) $app->screenshots()->max('sort_order');
                foreach ($files as $file) {
                    $path = $this->media->storeScreenshot($app->id, $file);
                    $stored[] = $path;
                    $app->screenshots()->create(['file_path' => $path, 'sort_order' => ++$order]);
                }
            });
        } catch (Throwable $exception) {
            foreach ($stored as $path) {
                $this->media->deleteIfUnreferenced($path);
            }
            throw $exception;
        }
    }
}
