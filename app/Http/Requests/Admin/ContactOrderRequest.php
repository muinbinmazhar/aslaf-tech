<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return $this->routeIs('admin.contact-links.move')
            ? ['direction' => ['required', Rule::in(['up', 'down'])]]
            : [
                'page' => ['required', 'integer', 'min:1'],
                'ids' => ['required', 'array', 'min:1', 'max:15'],
                'ids.*' => ['required', 'integer', 'distinct'],
            ];
    }
}
