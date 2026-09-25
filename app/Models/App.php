<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class App extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'logo_path',
        'android_url',
        'ios_url',
        'web_url',
        'youtube_url',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function screenshots(): HasMany
    {
        return $this->hasMany(AppScreenshot::class)->orderBy('sort_order')->orderBy('id');
    }

    public function destinations(): array
    {
        $links = [];

        foreach (['android_url' => 'Android', 'ios_url' => 'iOS', 'web_url' => 'Visit Web App'] as $field => $label) {
            $url = $this->{$field};

            if (! is_string($url) || ! filter_var($url, FILTER_VALIDATE_URL)
                || ! in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true)) {
                continue;
            }

            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            if ($field === 'android_url' && $host === 'play.google.com') {
                $label = 'Google Play / Android';
            } elseif ($field === 'ios_url' && $host === 'apps.apple.com') {
                $label = 'App Store / iOS';
            }

            $links[] = ['label' => $label, 'url' => $url];
        }

        return $links;
    }
}
