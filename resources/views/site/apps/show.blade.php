@extends('site.layouts.app')

@section('title', $app->name.' | ASLAF TECH')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($app->short_description ?: $app->description ?: 'Explore '.$app->name.' from ASLAF TECH.'), 155))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/site-app-screenshots.css') }}?v={{ filemtime(public_path('css/site-app-screenshots.css')) }}">
@endpush

@section('content')
    <section class="section detail-section"><div class="container">
        <a class="back-link" href="{{ route('apps.index') }}">← Back to All Apps</a>
        <div class="detail-grid">
            <div class="detail-image">
                @if($app->logo_path)<img src="{{ asset('storage/'.$app->logo_path) }}" alt="{{ $app->name }} thumbnail">@else<span class="app-image-placeholder" aria-hidden="true"><span>AT</span></span>@endif
            </div>
            <div class="detail-copy"><span class="eyebrow">ASLAF TECH APP</span><h1>{{ $app->name }}</h1>
                <div class="detail-media{{ $videoEmbedUrl ? ' has-video' : '' }}">
                    <div class="detail-actions">@include('site.partials.download-links', ['destinations' => $app->destinations()])</div>
                    @if($videoEmbedUrl)
                        <div class="detail-video">
                            <iframe src="{{ $videoEmbedUrl }}" title="{{ $app->name }} video" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    @endif
                </div>
                @if($app->short_description)<p class="detail-lead">{{ $app->short_description }}</p>@endif
                @if($app->description)<div class="detail-description">{{ $app->description }}</div>@endif
            </div>
        </div>
        @include('site.apps.partials.screenshot-gallery')
    </div></section>
@endsection

@if($app->screenshots->isNotEmpty())
    @push('scripts')
        <script src="{{ asset('js/site-app-screenshots.js') }}?v={{ filemtime(public_path('js/site-app-screenshots.js')) }}" defer></script>
    @endpush
@endif
