<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['title', 'short_description', 'badge_text', 'icon', 'sort_order', 'is_published'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'is_published' => 'boolean'];
    }

    public static function icons(): array
    {
        return ['web' => '⌘', 'code' => '{ }', 'mobile' => '▣', 'design' => '✦', 'brand' => '◈', 'interface' => '▤'];
    }

    public function iconSymbol(): string
    {
        return self::icons()[$this->icon] ?? '✦';
    }
}
