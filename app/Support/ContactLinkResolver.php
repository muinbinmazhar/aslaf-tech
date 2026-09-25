<?php

namespace App\Support;

use App\Models\ContactLink;

class ContactLinkResolver
{
    private const DOMAINS = [
        'telegram' => ['t.me', 'telegram.me'], 'facebook' => ['facebook.com', 'fb.com', 'fb.me', 'fb.watch'],
        'instagram' => ['instagram.com', 'instagr.am'], 'youtube' => ['youtube.com', 'youtu.be'],
        'linkedin' => ['linkedin.com', 'lnkd.in'], 'x' => ['x.com', 'twitter.com', 't.co'],
        'tiktok' => ['tiktok.com'], 'pinterest' => ['pinterest.com', 'pin.it'],
        'threads' => ['threads.net'], 'discord' => ['discord.com', 'discord.gg'],
        'reddit' => ['reddit.com'], 'github' => ['github.com'], 'gitlab' => ['gitlab.com'],
        'dribbble' => ['dribbble.com'], 'behance' => ['behance.net'],
        'figma' => ['figma.com'], 'upwork' => ['upwork.com'], 'fiverr' => ['fiverr.com'],
        'google_play' => ['play.google.com'], 'apple_app_store' => ['apps.apple.com'],
        'google_maps' => ['google.com', 'maps.google.com', 'maps.app.goo.gl', 'goo.gl'],
        'directions' => ['google.com', 'maps.google.com', 'maps.app.goo.gl', 'goo.gl'],
    ];

    public function error(string $type, string $value): ?string
    {
        if ($type === 'mobile') {
            return preg_match('/\A\+?[0-9 ()-]{7,30}\z/D', $value) && strlen(preg_replace('/\D/', '', $value)) >= 7
                ? null : 'Enter a valid phone number.';
        }
        if ($type === 'whatsapp') {
            return $this->whatsappDigits($value) ? null : 'Enter a valid Bangladesh WhatsApp number.';
        }
        if ($type === 'email') {
            return filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Enter a valid email address.';
        }
        if ($type === 'address') {
            return trim($value) !== '' ? null : 'Enter an office address.';
        }
        if (! $this->validHttpsUrl($value)) {
            return 'Enter a valid HTTPS URL.';
        }
        if (in_array($type, ['google_maps', 'directions'], true)) {
            return $this->validMapUrl($value, $type === 'directions') ? null : 'Enter a valid Google Maps URL.';
        }
        $host = strtolower((string) parse_url($value, PHP_URL_HOST));
        foreach (self::DOMAINS[$type] ?? [] as $domain) {
            if ($host === $domain || str_ends_with($host, '.'.$domain)) {
                return null;
            }
        }
        return isset(self::DOMAINS[$type]) ? 'Enter a URL for the selected platform.' : null;
    }

    public function validHttpsUrl(string $url): bool
    {
        $parts = parse_url($url);
        return filter_var($url, FILTER_VALIDATE_URL) !== false
            && is_array($parts) && strtolower($parts['scheme'] ?? '') === 'https'
            && ! empty($parts['host']) && ! isset($parts['user']) && ! isset($parts['pass']);
    }

    public function whatsappDigits(string $value): ?string
    {
        if (! preg_match('/\A\+?(?:8801[3-9][0-9]{8}|01[3-9][0-9]{8})\z/D', $value)) {
            return null;
        }
        $digits = ltrim($value, '+');
        return str_starts_with($digits, '01') ? '88'.$digits : $digits;
    }

    public function url(ContactLink $contact): ?string
    {
        $value = trim($contact->value);
        if ($this->error($contact->type, $value)) return null;

        return match ($contact->type) {
            'mobile' => 'tel:'.preg_replace('/[^+0-9]/', '', $value),
            'whatsapp' => 'https://wa.me/'.$this->whatsappDigits($value),
            'email' => 'mailto:'.$value,
            'address' => $contact->link_url && $this->validMapUrl($contact->link_url) ? $contact->link_url : null,
            default => $value,
        };
    }

    public function validMapUrl(string $url, bool $directions = false): bool
    {
        if (! $this->validHttpsUrl($url)) return false;
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);
        if ($host === 'maps.app.goo.gl') return $path !== '' && $path !== '/';
        if ($host === 'goo.gl') return str_starts_with($path, '/maps/');
        if (! ($host === 'google.com' || str_ends_with($host, '.google.com'))) return false;
        if ($directions) return str_starts_with($path, '/maps/dir') || str_starts_with($path, '/maps/preview/dir');
        return str_starts_with($path, '/maps') || $host === 'maps.google.com';
    }
}
