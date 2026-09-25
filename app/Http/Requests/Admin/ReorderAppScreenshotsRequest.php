<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReorderAppScreenshotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'screenshot_ids' => ['required', 'array', 'max:30'],
            'screenshot_ids.*' => ['required', 'integer', 'distinct'],
        ];
    }
}
