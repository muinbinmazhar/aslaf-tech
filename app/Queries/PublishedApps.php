<?php

namespace App\Queries;

use App\Models\App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PublishedApps
{
    public function ordered(): Builder
    {
        return App::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    public function menu(): Collection
    {
        return $this->ordered()->limit(6)->get(['id', 'name', 'slug']);
    }

    public function featured(): Collection
    {
        return $this->ordered()->limit(6)->get();
    }

    public function paginated(): LengthAwarePaginator
    {
        return $this->ordered()->paginate(12);
    }

    public function findBySlug(string $slug): App
    {
        return $this->ordered()
            ->where('slug', $slug)
            ->with(['screenshots' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->firstOrFail();
    }
}
