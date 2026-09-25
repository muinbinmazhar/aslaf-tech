<?php

namespace App\Support;

use App\Models\App;
use App\Models\AppScreenshot;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AppMedia
{
    public function storeThumbnail(UploadedFile $file): string
    {
        $path = Storage::disk('public')->putFile('app-thumbnails', $file);

        if ($path === false) {
            throw ValidationException::withMessages([
                'logo' => 'Thumbnail সংরক্ষণ করা যায়নি। আবার চেষ্টা করুন।',
            ]);
        }

        return $path;
    }

    public function storeScreenshot(int $appId, UploadedFile $file): string
    {
        $path = Storage::disk('public')->putFile("app-screenshots/{$appId}", $file);

        if ($path === false) {
            throw ValidationException::withMessages(['screenshots' => 'Screenshot সংরক্ষণ করা যায়নি। আবার চেষ্টা করুন।']);
        }

        return $path;
    }

    public function deleteIfUnreferenced(?string $path): void
    {
        if ($path === null || ! preg_match('~\A(?:app-thumbnails|app-logos)/[A-Za-z0-9][A-Za-z0-9._-]*\z|\Aapp-screenshots/[1-9][0-9]*/[A-Za-z0-9][A-Za-z0-9._-]*\z~', $path)) {
            return;
        }

        if (App::query()->where('logo_path', $path)->exists()
            || AppScreenshot::query()->where('file_path', $path)->exists()) {
            return;
        }

        $disk = Storage::disk('public');
        if ($disk->exists($path) && ! $disk->delete($path)) {
            throw new \RuntimeException('App media file could not be deleted.');
        }
    }
}
