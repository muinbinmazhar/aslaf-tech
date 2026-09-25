<?php

namespace App\Queries;

use App\Models\ContactLink;
use App\Support\ContactLinkResolver;
use Illuminate\Database\Eloquent\Collection;

class VisibleContactLinks
{
    public function __construct(private ContactLinkResolver $resolver) {}

    public function forLocation(string $location): Collection
    {
        $column = match ($location) {
            'homepage' => 'show_on_homepage', 'footer' => 'show_in_footer',
            'header' => 'show_in_header', default => throw new \InvalidArgumentException('Unknown contact location.'),
        };

        return ContactLink::query()->where('is_visible', true)->where($column, true)
            ->orderBy('sort_order')->orderBy('id')->get()
            ->each(fn (ContactLink $link) => $link->setAttribute('public_url', $this->resolver->url($link)));
    }
}
