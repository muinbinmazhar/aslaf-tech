<?php

namespace App\Actions;

use App\Models\WebsiteSetting;
use App\Support\WebsiteMedia;
use Throwable;

class SaveWebsiteSettings
{
    public function __construct(private WebsiteMedia $media) {}

    public function handle(array $data): void
    {
        $settings = WebsiteSetting::query()->firstOrCreate(['id' => 1], WebsiteSetting::defaults());
        $oldPaths = [$settings->logo_path, $settings->favicon_path];
        $newPaths = [];

        foreach (['logo' => ['logo_path', 'website-logos'], 'favicon' => ['favicon_path', 'website-favicons']] as $field => [$column, $directory]) {
            if (isset($data[$field])) {
                try {
                    $newPaths[] = $data[$column] = $this->media->store($data[$field], $directory, $field);
                } catch (Throwable $exception) {
                    foreach ($newPaths as $path) $this->media->deleteIfUnreferenced($path);
                    throw $exception;
                }
            } elseif ($data['remove_'.$field] ?? false) {
                $data[$column] = null;
            }
            unset($data[$field], $data['remove_'.$field]);
        }

        try {
            $settings->update($data);
        } catch (Throwable $exception) {
            foreach ($newPaths as $path) $this->media->deleteIfUnreferenced($path);
            throw $exception;
        }

        foreach ($oldPaths as $path) $this->media->deleteIfUnreferenced($path);
    }
}
