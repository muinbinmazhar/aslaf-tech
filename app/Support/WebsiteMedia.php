<?php

namespace App\Support;

use App\Models\HomepageContent;
use App\Models\PortfolioProject;
use App\Models\WebsiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class WebsiteMedia
{
    public function store(UploadedFile $file, string $directory, string $field): string
    {
        $path = Storage::disk('public')->putFile($directory, $file);

        if ($path === false) {
            throw ValidationException::withMessages([$field => 'File সংরক্ষণ করা যায়নি। আবার চেষ্টা করুন।']);
        }

        return $path;
    }

    public function heroVisualPath(?string $path): ?string
    {
        return $this->isManagedHeroVisual($path) && Storage::disk('public')->exists($path) ? $path : null;
    }

    public function deleteHeroVisual(?string $path): void
    {
        if (! $this->isManagedHeroVisual($path)) {
            return;
        }
        if (HomepageContent::query()->where('content->visual_path', $path)->exists()
            || WebsiteSetting::query()->where('logo_path', $path)->orWhere('favicon_path', $path)->exists()
            || PortfolioProject::query()->where('thumbnail_path', $path)->exists()) {
            return;
        }
        Storage::disk('public')->delete($path);
    }

    private function isManagedHeroVisual(?string $path): bool
    {
        return is_string($path) && preg_match('~\Ahomepage/hero/[A-Za-z0-9_-]+\.(?:jpg|jpeg|png|webp)\z~', $path) === 1;
    }

    public function deleteIfUnreferenced(?string $path): void
    {
        if (! $path || ! preg_match('~\A(?:website-logos|website-favicons|portfolio-thumbnails)/[A-Za-z0-9][A-Za-z0-9._-]*\z~', $path)) {
            return;
        }

        if (WebsiteSetting::query()->where('logo_path', $path)->orWhere('favicon_path', $path)->exists()
            || PortfolioProject::query()->where('thumbnail_path', $path)->exists()) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
