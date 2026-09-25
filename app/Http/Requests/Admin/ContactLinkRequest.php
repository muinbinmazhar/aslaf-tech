<?php

namespace App\Http\Requests\Admin;

use App\Support\ContactLinkResolver;
use App\Support\ContactTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ContactLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        $valueRules = ['required', 'string', 'max:2048'];
        if ($this->input('type') === 'email') $valueRules[] = 'email';

        return [
            'type' => ['required', Rule::in(array_keys(ContactTypes::all()))],
            'display_name' => ['required', 'string', 'max:255'],
            'value' => $valueRules,
            'supporting_text' => ['nullable', 'string', 'max:500'],
            'icon_key' => ['nullable', Rule::in(ContactTypes::customIcons())],
            'link_url' => ['nullable', 'string', 'max:2048'],
            'show_on_homepage' => ['required', 'boolean'],
            'show_in_footer' => ['required', 'boolean'],
            'show_in_header' => ['required', 'boolean'],
            'is_visible' => ['required', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->count() > 0) return;
            $resolver = app(ContactLinkResolver::class);
            $type = $this->string('type')->toString();
            $error = $resolver->error($type, trim($this->string('value')->toString()));
            if ($error) $validator->errors()->add('value', $error);

            $map = trim($this->string('link_url')->toString());
            if ($map !== '' && ($type !== 'address' || ! $resolver->validMapUrl($map))) {
                $validator->errors()->add('link_url', 'Only an office address may use a valid HTTPS Google Maps URL.');
            }
            if (! $this->boolean('show_on_homepage') && ! $this->boolean('show_in_footer') && ! $this->boolean('show_in_header')) {
                $validator->errors()->add('show_on_homepage', 'Choose at least one display location.');
            }
        }];
    }
}
