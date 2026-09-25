<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#243456">
    <title>@yield('title', 'Admin') | ASLAF TECH</title>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}?v={{ filemtime(public_path('css/fonts.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v={{ filemtime(public_path('css/theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-website.css') }}?v={{ filemtime(public_path('css/admin-website.css')) }}">
    @stack('styles')
    <script src="{{ asset('js/admin.js') }}" defer></script>
    @stack('scripts')
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    <div class="admin-shell">
        @include('admin.partials.sidebar')
        <div class="admin-main">
            @include('admin.partials.header')
            <main id="main-content" class="content" tabindex="-1">@yield('content')</main>
        </div>
    </div>
</body>
</html>
