<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#243456">
    <meta name="description" content="{{ trim($__env->yieldContent('meta_description', $settings->seo_description ?: 'ASLAF TECH builds websites, software, mobile apps and creative digital experiences.')) }}">
    <title>{{ trim($__env->yieldContent('title', $settings->seo_title ?: 'ASLAF TECH — Digital Products & Creative Design')) }}</title>
    @if($settings->favicon_path)<link rel="icon" href="{{ asset('storage/'.$settings->favicon_path) }}">@endif
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}?v={{ filemtime(public_path('css/fonts.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v={{ filemtime(public_path('css/theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/site-pages.css') }}?v={{ filemtime(public_path('css/site-pages.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/site-contacts.css') }}?v={{ filemtime(public_path('css/site-contacts.css')) }}">
    @stack('styles')
    <script src="{{ asset('js/site-navigation.js') }}?v={{ filemtime(public_path('js/site-navigation.js')) }}" defer></script>
    <script src="{{ asset('js/site-downloads.js') }}" defer></script>
    <script src="{{ asset('js/site-footer.js') }}?v={{ filemtime(public_path('js/site-footer.js')) }}" defer></script>
    @stack('scripts')
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    @include('site.partials.header')
    <main id="main-content">@yield('content')</main>
    @include('site.partials.footer')
</body>
</html>
