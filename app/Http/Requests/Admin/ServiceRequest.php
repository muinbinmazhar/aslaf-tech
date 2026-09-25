<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('badge_text') && is_string($this->input('badge_text'))) {
            $this->merge(['badge_text' => trim($this->input('badge_text')) ?: null]);
        }
    }

    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:1000'],
            'badge_text' => ['nullable', 'string', 'max:40'],
            'icon' => ['required', Rule::in(array_keys(Service::icons()))],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'is_published' => ['required', 'boolean'],
        ];
    }
}
