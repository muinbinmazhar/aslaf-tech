<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        if (blank($this->input('slug')) && is_string($this->input('title'))) {
            $this->merge(['slug' => Str::slug($this->input('title'))]);
        }
    }

    public function rules(): array
    {
        $uniqueSlug = Rule::unique('portfolio_projects', 'slug');
        if ($this->route('project')) {
            $uniqueSlug->ignore($this->route('project'));
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $uniqueSlug],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:10000'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', Rule::prohibitedIf($this->boolean('remove_thumbnail'))],
            'remove_thumbnail' => ['sometimes', 'boolean'],
            'category' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'url:http,https', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return ['slug.unique' => 'এই Slug ইতিমধ্যে ব্যবহৃত হয়েছে।', 'slug.required' => 'ইংরেজি Slug লিখুন।'];
    }
}
