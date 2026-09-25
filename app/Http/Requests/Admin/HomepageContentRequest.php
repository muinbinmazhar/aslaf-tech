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

    public function rules(): array
    {
        $rules = [];

        foreach (HomepageContent::defaults() as $section => $fields) {
            foreach ($fields as $key => $default) {
                $name = "content.{$section}.{$key}";
                $rules[$name] = [in_array($key, ['heading', 'eyebrow'], true) ? 'required' : 'nullable', 'string', 'max:'.($key === 'description' ? 2000 : 255)];

                if (str_ends_with($key, '_link')) {
                    $rules[$name][] = function ($attribute, $value, $fail) {
                        if ($value === null || $value === '') return;
                        if (! is_string($value)) return;
                        if (preg_match('~\A(?:#[A-Za-z][A-Za-z0-9_-]*|/(?!/)[A-Za-z0-9/#?&=_%-]*)\z~', $value)) return;
                        if (filter_var($value, FILTER_VALIDATE_URL)
                            && in_array(strtolower((string) parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], true)) return;
                        $fail('Use a section anchor, internal path or valid HTTPS/HTTP URL.');
                    };
                }
            }
        }

        return $rules;
    }
}
