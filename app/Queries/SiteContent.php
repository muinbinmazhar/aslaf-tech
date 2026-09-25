<?php

namespace App\Queries;

use App\Models\HomepageContent;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\WebsiteSetting;
use Illuminate\Database\Eloquent\Collection;

class SiteContent
{
    public function settings(): WebsiteSetting
    {
        return WebsiteSetting::query()->first() ?? new WebsiteSetting(WebsiteSetting::defaults());
    }

    public function homepage(): array
    {
        $content = HomepageContent::defaults();

        foreach (HomepageContent::query()->get() as $section) {
            if (isset($content[$section->section])) {
                $content[$section->section] = array_replace($content[$section->section], $section->content ?? []);
            }
        }

        return $content;
    }

    public function services(): Collection
    {
        return Service::query()->where('is_published', true)
            ->orderBy('sort_order')->orderByDesc('id')->get();
    }

    public function projects(): Collection
    {
        return PortfolioProject::query()->where('is_published', true)
            ->orderBy('sort_order')->orderByDesc('id')->limit(6)->get();
    }

    public function projectBySlug(string $slug): PortfolioProject
    {
        return PortfolioProject::query()->where('is_published', true)
            ->where('slug', $slug)->firstOrFail();
    }
}
