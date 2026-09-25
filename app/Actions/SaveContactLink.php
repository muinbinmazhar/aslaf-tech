<?php

namespace App\Actions;

use App\Models\ContactLink;

class SaveContactLink
{
    public function handle(array $data, ?ContactLink $contact = null): ContactLink
    {
        $data['display_name'] = trim($data['display_name']);
        $data['value'] = trim($data['value']);
        $data['supporting_text'] = isset($data['supporting_text']) ? trim($data['supporting_text']) ?: null : null;
        $data['icon_key'] = $data['type'] === 'custom' ? ($data['icon_key'] ?? 'link') : null;
        $data['link_url'] = $data['type'] === 'address' ? (trim($data['link_url'] ?? '') ?: null) : null;

        if (! $contact) {
            $contact = new ContactLink();
            $lastOrder = ContactLink::query()->max('sort_order');
            $data['sort_order'] = $lastOrder === null ? 0 : (int) $lastOrder + 1;
        }
        $contact->fill($data)->save();

        return $contact;
    }
}
