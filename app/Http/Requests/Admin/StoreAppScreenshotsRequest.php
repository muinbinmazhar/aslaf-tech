<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppScreenshotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'screenshots' => ['required', 'array', 'min:1', 'max:10'],
            'screenshots.*' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=320,min_height=320'],
        ];
    }

    public function messages(): array
    {
        return [
            'screenshots.max' => 'Choose no more than 10 images at once.',
            'screenshots.*.dimensions' => 'Each screenshot must be at least 320 × 320 pixels.',
        ];
    }
}
