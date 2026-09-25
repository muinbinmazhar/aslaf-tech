<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppScreenshot extends Model
{
    protected $fillable = [
        'app_id',
        'file_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }
}
