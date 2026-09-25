<?php

namespace Database\Seeders;

use App\Models\HomepageContent;
use App\Models\Service;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        WebsiteSetting::query()->firstOrCreate(['id' => 1], WebsiteSetting::defaults());

        foreach (HomepageContent::defaults() as $section => $content) {
            HomepageContent::query()->firstOrCreate(['section' => $section], ['content' => $content]);
        }

        $services = [
            ['Website Development', 'Responsive websites designed to present your business clearly across devices.', 'web'],
            ['Software Development', 'Practical software tailored to workflows, users and the goals behind them.', 'code'],
            ['Mobile App Development', 'Mobile experiences built around simple interactions and real user needs.', 'mobile'],
            ['Graphic Design', 'Clear visual communication for digital and printed materials.', 'design'],
            ['Branding & Identity', 'Consistent visual identities that help ideas feel recognizable and coherent.', 'brand'],
            ['UI/UX Design', 'Interfaces shaped for usability, accessibility and purposeful interaction.', 'interface'],
        ];

        foreach ($services as $order => [$title, $description, $icon]) {
            Service::query()->firstOrCreate(['title' => $title], [
                'short_description' => $description,
                'icon' => $icon,
                'sort_order' => $order,
                'is_published' => true,
            ]);
        }
    }
}
