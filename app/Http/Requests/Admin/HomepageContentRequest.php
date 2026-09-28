<?php

namespace App\Http\Requests\Admin;

use App\Models\HomepageContent;
use Illuminate\Foundation\Http\FormRequest;

class HomepageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $content = $this->input('content', []);
        if (is_array($content) && is_array($content['hero'] ?? null)) {
            foreach (['visual_show_rings', 'visual_show_labels'] as $field) {
                $content['hero'][$field] ??= false;
            }
            $this->merge(['content' => $content]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'hero_visual' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_hero_visual' => ['sometimes', 'boolean'],
        ];

        foreach (HomepageContent::defaults() as $section => $fields) {
            foreach ($fields as $key => $default) {
                $name = "content.{$section}.{$key}";
                if ($key === 'visual_path') {
                    $rules[$name] = ['exclude'];

                    continue;
                }
                if (in_array($key, ['visual_show_rings', 'visual_show_labels'], true)) {
                    $rules[$name] = ['required', 'boolean'];

                    continue;
                }
                if (str_starts_with($key, 'visual_')) {
                    $rules[$name] = ['nullable', 'string', 'max:'.($key === 'visual_alt' ? 255 : 60), 'not_regex:/[<>]/u'];

                    continue;
                }
                $rules[$name] = [in_array($key, ['heading', 'eyebrow'], true) ? 'required' : 'nullable', 'string', 'max:'.($key === 'description' ? 2000 : 255)];

                if (str_ends_with($key, '_link')) {
                    $rules[$name][] = function ($attribute, $value, $fail) {
                        if ($value === null || $value === '') {
                            return;
                        }
                        if (! is_string($value)) {
                            return;
                        }
                        if (preg_match('~\A(?:#[A-Za-z][A-Za-z0-9_-]*|/(?!/)[A-Za-z0-9/#?&=_%-]*)\z~', $value)) {
                            return;
                        }
                        if (filter_var($value, FILTER_VALIDATE_URL)
                            && in_array(strtolower((string) parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], true)) {
                            return;
                        }
                        $fail('Use a section anchor, internal path or valid HTTPS/HTTP URL.');
                    };
                }
            }
        }

        return $rules;
    }
}
