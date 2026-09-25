<?php

namespace App\Actions;

use App\Models\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReorderAppScreenshots
{
    public function handle(App $app, array $ids): void
    {
        DB::transaction(function () use ($app, $ids): void {
            App::query()->whereKey($app->id)->lockForUpdate()->firstOrFail();
            $current = $app->screenshots()->pluck('id')->all();
            if (count($ids) !== count($current)
                || array_diff($ids, $current)
                || array_diff($current, $ids)) {
                throw ValidationException::withMessages(['screenshot_ids' => 'The screenshot order is out of date. Refresh and try again.']);
            }

            foreach ($ids as $index => $id) {
                $app->screenshots()->whereKey($id)->update(['sort_order' => $index + 1]);
            }
        });
    }
}
