<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdateAppRequest extends StoreAppRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['logo'][] = Rule::prohibitedIf($this->boolean('remove_thumbnail'));
        $rules['remove_thumbnail'] = ['sometimes', 'boolean'];

        return $rules;
    }

    protected function slugUniqueRule(): Unique
    {
        return Rule::unique('apps', 'slug')->ignore($this->route('app'));
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'logo.prohibited' => 'নতুন Thumbnail নির্বাচন করলে Remove Thumbnail অপশন বন্ধ করুন।',
        ]);
    }
}
