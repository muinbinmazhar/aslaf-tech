@extends('admin.layouts.app')
@section('title', 'Website Settings')
@push('scripts')<script src="{{ asset('js/admin-website-media.js') }}" defer></script>@endpush
@section('content')
<div class="page-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>Website Settings</h1><p class="page-description">Manage public branding, SEO, footer and verified contact details.</p></div></div>
@include('admin.website.partials.alerts')
<form class="wm-form" method="POST" action="{{ route('admin.website.settings.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
    <section class="wm-panel"><h2>Branding &amp; SEO</h2><div class="wm-grid">
        @foreach(['website_name' => 'Website Name', 'tagline' => 'Website Tagline', 'seo_title' => 'Default SEO Title', 'seo_description' => 'Default SEO Description', 'footer_description' => 'Footer Description', 'copyright_text' => 'Footer Copyright Text'] as $field => $label)
            <div class="wm-field {{ in_array($field, ['seo_description', 'footer_description']) ? 'wide' : '' }}"><label for="{{ $field }}">{{ $label }}@if($field === 'website_name') * @endif</label>
                @if(in_array($field, ['seo_description', 'footer_description']))<textarea id="{{ $field }}" name="{{ $field }}" rows="3" @error($field) aria-invalid="true" @enderror>{{ old($field, $settings->{$field}) }}</textarea>@else<input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" @if($field === 'website_name') required @endif @error($field) aria-invalid="true" @enderror>@endif
                @error($field)<span class="wm-error">{{ $message }}</span>@enderror
            </div>
        @endforeach
        @include('admin.website.partials.media-field', ['field' => 'logo', 'label' => 'Website Logo', 'path' => $settings->logo_path, 'accept' => 'image/jpeg,image/png,image/webp', 'hint' => 'JPG, PNG or WebP · Max 2 MB. SVG is not accepted.', 'showRemove' => true])
        @include('admin.website.partials.media-field', ['field' => 'favicon', 'label' => 'Favicon', 'path' => $settings->favicon_path, 'accept' => 'image/png,image/x-icon,image/vnd.microsoft.icon', 'hint' => 'PNG or ICO · Max 512 KB.', 'showRemove' => true])
    </div></section>
    <section class="wm-panel"><h2>Legacy Contact Information</h2><p>These saved values are retained for reference. Public contact details are managed in <a href="{{ route('admin.contact-links.index') }}">Contact &amp; Social Links</a>. Changes here do not update public contact links; use <code>php artisan contacts:import-legacy</code> to import newly added legacy values once.</p><div class="wm-grid">
        @foreach(['contact_email' => 'Contact Email', 'phone' => 'Phone Number', 'whatsapp' => 'WhatsApp Number', 'office_address' => 'Office Address', 'facebook_url' => 'Facebook URL', 'linkedin_url' => 'LinkedIn URL', 'github_url' => 'GitHub URL'] as $field => $label)
            <div class="wm-field {{ $field === 'office_address' ? 'wide' : '' }}"><label for="{{ $field }}">{{ $label }}</label>
                @if($field === 'office_address')<textarea id="{{ $field }}" name="{{ $field }}" rows="3" @error($field) aria-invalid="true" @enderror>{{ old($field, $settings->{$field}) }}</textarea>@else<input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" @if(str_ends_with($field, '_url')) type="url" @elseif($field === 'contact_email') type="email" @endif @error($field) aria-invalid="true" @enderror>@endif
                @if($field === 'whatsapp')<small>Use international format, for example +8801…</small>@endif
                @error($field)<span class="wm-error">{{ $message }}</span>@enderror
            </div>
        @endforeach
    </div></section>
    <div class="wm-actions"><button class="wm-primary" type="submit">Save Settings</button></div>
</form>
@endsection
