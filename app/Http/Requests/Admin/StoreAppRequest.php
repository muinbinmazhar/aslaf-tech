<?php

namespace App\Http\Requests\Admin;

use App\Support\YouTubeVideo;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        if (blank($this->input('slug')) && is_string($this->input('name'))) {
            $this->merge(['slug' => Str::slug($this->input('name'))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $this->slugUniqueRule()],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'android_url' => ['nullable', 'url:http,https', 'max:255'],
            'ios_url' => ['nullable', 'url:http,https', 'max:255'],
            'web_url' => ['nullable', 'url:http,https', 'max:255'],
            'youtube_url' => ['nullable', 'url:http,https', 'max:255', function (string $attribute, mixed $value, Closure $fail): void {
                if (YouTubeVideo::embedUrl($value) === null) {
                    $fail('Enter a valid YouTube video URL.');
                }
            }],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }

    protected function slugUniqueRule(): Unique
    {
        return Rule::unique('apps', 'slug');
    }

    public function messages(): array
    {
        return [
            'slug.required' => 'নাম থেকে Slug তৈরি করা যায়নি। ইংরেজি অক্ষর দিয়ে Slug লিখুন।',
            'slug.regex' => 'Slug-এ শুধু ছোট ইংরেজি অক্ষর, সংখ্যা ও মাঝখানে হাইফেন ব্যবহার করুন।',
            'slug.unique' => 'এই Slug ইতিমধ্যে ব্যবহার করা হয়েছে। অন্য Slug লিখুন।',
            'logo.image' => 'Thumbnail অবশ্যই একটি বৈধ ছবি হতে হবে।',
            'logo.mimes' => 'Thumbnail-এ JPG, PNG অথবা WebP ছবি ব্যবহার করুন।',
            'logo.max' => 'Thumbnail সর্বোচ্চ ২ MB হতে পারবে।',
        ];
    }
}
