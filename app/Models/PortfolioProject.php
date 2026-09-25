<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioProject extends Model
{
    protected $fillable = [
        'title', 'slug', 'short_description', 'description', 'thumbnail_path',
        'category', 'project_url', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'is_published' => 'boolean'];
    }

    public function safeProjectUrl(): ?string
    {
        $url = $this->project_url;

        return is_string($url) && filter_var($url, FILTER_VALIDATE_URL)
            && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true)
                ? $url : null;
    }
}
