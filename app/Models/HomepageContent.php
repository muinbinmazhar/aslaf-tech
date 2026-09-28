<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $fillable = ['section', 'content'];

    protected function casts(): array
    {
        return ['content' => 'array'];
    }

    public static function defaults(): array
    {
        return [
            'hero' => [
                'visual_path' => null,
                'visual_alt' => 'ASLAF TECH — Design & Development',
                'visual_label_one' => '01 / DESIGN',
                'visual_label_two' => '02 / BUILD',
                'visual_show_rings' => true,
                'visual_show_labels' => true,
                'eyebrow' => 'TECHNOLOGY MEETS CREATIVITY',
                'heading' => 'Digital solutions built with purpose.',
                'description' => 'From websites and software to mobile apps and creative design, ASLAF TECH brings ideas into the digital world with clarity and care.',
                'primary_label' => 'Explore Our Services', 'primary_link' => '#services',
                'secondary_label' => 'Explore Our Apps', 'secondary_link' => '/apps',
            ],
            'services' => ['eyebrow' => 'WHAT WE DO', 'heading' => 'Services for the digital world.', 'description' => 'Thoughtful technology and design work to help shape useful digital experiences.'],
            'apps' => ['eyebrow' => 'OUR PRODUCTS', 'heading' => 'Explore Our Apps.', 'description' => 'Discover published apps and digital products from ASLAF TECH.', 'view_all_label' => 'View All Apps'],
            'portfolio' => ['eyebrow' => 'OUR WORK', 'heading' => 'Ideas deserve a thoughtful digital home.', 'description' => 'Our portfolio space will highlight work across technology and design as projects become available to share. Have a project in mind? Explore how we work and find our contact information below.'],
            'about' => ['eyebrow' => 'ABOUT US', 'heading' => 'About ASLAF TECH', 'description' => 'ASLAF TECH works at the intersection of technology and creative design. We focus on websites, software, mobile apps, graphic design and brand experiences that are clear, useful and carefully made.'],
            'contact' => ['eyebrow' => 'GET IN TOUCH', 'heading' => "Let's start a conversation.", 'description' => 'Share a few details about your project and we will review your message.'],
        ];
    }
}
