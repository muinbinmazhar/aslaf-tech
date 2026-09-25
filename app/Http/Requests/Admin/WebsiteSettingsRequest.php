<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebsiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'website_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'footer_description' => ['nullable', 'string', 'max:1000'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', Rule::prohibitedIf($this->boolean('remove_logo'))],
            'favicon' => ['nullable', 'file', 'mimes:png,ico', 'max:512', Rule::prohibitedIf($this->boolean('remove_favicon'))],
            'remove_logo' => ['sometimes', 'boolean'],
            'remove_favicon' => ['sometimes', 'boolean'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/\A\+?[0-9 ()-]{7,30}\z/'],
            'whatsapp' => ['nullable', 'regex:/\A\+[1-9][0-9]{7,14}\z/'],
            'office_address' => ['nullable', 'string', 'max:1000'],
            'facebook_url' => ['nullable', 'url:http,https', 'max:255'],
            'linkedin_url' => ['nullable', 'url:http,https', 'max:255'],
            'github_url' => ['nullable', 'url:http,https', 'max:255'],
        ];
    }
}
