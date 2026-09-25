<?php

namespace App\Actions;

use App\Models\HomepageContent;
use Illuminate\Support\Facades\DB;

class SaveHomepageContent
{
    public function handle(array $sections): void
    {
        DB::transaction(function () use ($sections) {
            foreach (HomepageContent::defaults() as $section => $fields) {
                HomepageContent::query()->updateOrCreate(
                    ['section' => $section],
                    ['content' => array_replace($fields, $sections[$section] ?? [])],
                );
            }
        });
    }
}
