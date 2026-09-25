<?php

namespace App\Support;

use Illuminate\Support\Collection;

class ContactTypes
{
    // Icons are our own neutral symbols. No platform mark is represented as an official logo.
    private const TYPES = [
        'Contact Us' => [
            'mobile' => ['Mobile / Phone', 'phone'], 'whatsapp' => ['WhatsApp', 'chat'],
            'email' => ['Email', 'mail'], 'address' => ['Office Address / Location', 'pin'],
            'google_maps' => ['Google Maps', 'pin'], 'directions' => ['Get Directions', 'directions'],
            'website' => ['Website', 'globe'], 'telegram' => ['Telegram', 'chat'],
        ],
        'Follow Us' => [
            'facebook' => ['Facebook', 'facebook'], 'instagram' => ['Instagram', 'instagram'],
            'youtube' => ['YouTube', 'video'], 'linkedin' => ['LinkedIn', 'social'],
            'x' => ['X / Twitter', 'social'], 'tiktok' => ['TikTok', 'video'],
            'pinterest' => ['Pinterest', 'social'], 'threads' => ['Threads', 'social'],
            'discord' => ['Discord', 'chat'], 'reddit' => ['Reddit', 'social'],
        ],
        'Developer & Creative' => [
            'github' => ['GitHub', 'code'], 'gitlab' => ['GitLab', 'code'],
            'dribbble' => ['Dribbble', 'creative'], 'behance' => ['Behance', 'creative'],
            'figma' => ['Figma Community', 'creative'], 'upwork' => ['Upwork', 'work'],
            'fiverr' => ['Fiverr', 'work'], 'google_play' => ['Google Play Store', 'store'],
            'apple_app_store' => ['Apple App Store', 'store'], 'custom' => ['Custom Link', 'link'],
        ],
    ];

    public static function groups(): array { return self::TYPES; }

    public static function all(): array
    {
        return array_merge(...array_values(self::TYPES));
    }

    public static function grouped(Collection $contacts): array
    {
        $grouped = array_fill_keys(array_keys(self::TYPES), []);

        foreach ($contacts as $contact) {
            foreach (self::TYPES as $group => $types) {
                if (isset($types[$contact->type])) {
                    $grouped[$group][] = $contact;
                    break;
                }
            }
        }

        return array_filter($grouped);
    }

    public static function label(string $type): string
    {
        return self::all()[$type][0] ?? 'Contact';
    }

    public static function icon(string $type, ?string $custom = null): string
    {
        return $type === 'custom' && in_array($custom, self::customIcons(), true)
            ? $custom : (self::all()[$type][1] ?? 'link');
    }

    public static function customIcons(): array
    {
        return ['link', 'globe', 'chat', 'video', 'code', 'creative', 'work', 'store'];
    }
}
