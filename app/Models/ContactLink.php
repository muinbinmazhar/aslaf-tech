<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLink extends Model
{
    protected $fillable = [
        'type', 'display_name', 'value', 'supporting_text', 'icon_key', 'link_url',
        'show_on_homepage', 'show_in_footer', 'show_in_header', 'is_visible', 'sort_order', 'legacy_key',
    ];

    protected function casts(): array
    {
        return [
            'show_on_homepage' => 'boolean', 'show_in_footer' => 'boolean',
            'show_in_header' => 'boolean', 'is_visible' => 'boolean', 'sort_order' => 'integer',
        ];
    }
}
