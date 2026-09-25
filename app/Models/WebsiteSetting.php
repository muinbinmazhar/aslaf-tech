<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'website_name', 'tagline', 'logo_path', 'favicon_path', 'seo_title',
        'seo_description', 'footer_description', 'copyright_text',
        'contact_email', 'phone', 'whatsapp', 'office_address',
        'facebook_url', 'linkedin_url', 'github_url',
    ];

    public static function defaults(): array
    {
        return [
            'website_name' => 'ASLAF TECH',
            'tagline' => 'BUILDING DIGITAL POSSIBILITIES',
            'seo_title' => 'ASLAF TECH — Digital Products & Creative Design',
            'seo_description' => 'ASLAF TECH builds websites, software, mobile apps and creative digital experiences.',
            'footer_description' => 'Technology, software and creative design for purposeful digital experiences.',
            'copyright_text' => 'ASLAF TECH. All rights reserved.',
        ];
    }

    public function whatsappUrl(): ?string
    {
        return $this->whatsapp ? 'https://wa.me/'.ltrim($this->whatsapp, '+') : null;
    }

    public function phoneUrl(): ?string
    {
        return $this->phone ? 'tel:'.preg_replace('/[^+0-9]/', '', $this->phone) : null;
    }

    public function socialLinks(): array
    {
        $links = [];
        foreach (['facebook_url' => 'Facebook', 'linkedin_url' => 'LinkedIn', 'github_url' => 'GitHub'] as $field => $label) {
            $url = $this->{$field};
            if (is_string($url) && filter_var($url, FILTER_VALIDATE_URL)
                && in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true)) {
                $links[] = ['label' => $label, 'url' => $url];
            }
        }

        return $links;
    }
}
