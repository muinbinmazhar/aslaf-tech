<?php

namespace App\Actions;

use App\Models\ContactLink;
use App\Models\WebsiteSetting;
use App\Support\ContactLinkResolver;

class ImportLegacyContacts
{
    public function __construct(private ContactLinkResolver $resolver) {}

    public function handle(): int
    {
        $settings = WebsiteSetting::query()->first();
        if (! $settings) return 0;

        $fields = [
            'contact_email' => ['email', 'Email'], 'phone' => ['mobile', 'Phone'],
            'whatsapp' => ['whatsapp', 'WhatsApp'], 'office_address' => ['address', 'Office Address'],
            'facebook_url' => ['facebook', 'Facebook'], 'linkedin_url' => ['linkedin', 'LinkedIn'],
            'github_url' => ['github', 'GitHub'],
        ];
        $created = 0;
        foreach ($fields as $field => [$type, $name]) {
            $value = trim((string) $settings->{$field});
            if ($value === '' || $this->resolver->error($type, $value)) continue;
            $matching = ContactLink::query()->where('legacy_key', $field)
                ->orWhere(fn ($query) => $query->where('type', $type)->where('value', $value))->exists();
            if ($matching) continue;
            ContactLink::query()->create([
                'type' => $type, 'display_name' => $name, 'value' => $value,
                'show_on_homepage' => true, 'show_in_footer' => $type !== 'address',
                'show_in_header' => false, 'is_visible' => true, 'sort_order' => 0, 'legacy_key' => $field,
            ]);
            $created++;
        }
        return $created;
    }
}
