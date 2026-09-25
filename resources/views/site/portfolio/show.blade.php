@extends('site.layouts.app')

@section('title', $project->title.' | '.$settings->website_name)
@section('meta_description', \Illuminate\Support\Str::limit($project->short_description ?: $project->description ?: $project->title, 155))

@section('content')
<section class="section detail-section"><div class="container"><a class="back-link" href="{{ route('home') }}#portfolio">← Back to Portfolio</a><div class="detail-grid"><div class="detail-image">@if($project->thumbnail_path)<img src="{{ asset('storage/'.$project->thumbnail_path) }}" alt="{{ $project->title }} thumbnail">@else<span class="app-image-placeholder" aria-hidden="true"><span>AT</span></span>@endif</div><div class="detail-copy"><span class="eyebrow">{{ $project->category ?: 'PORTFOLIO' }}</span><h1>{{ $project->title }}</h1>@if($project->short_description)<p class="detail-lead">{{ $project->short_description }}</p>@endif @if($project->description)<div class="detail-description">{{ $project->description }}</div>@endif @if($project->safeProjectUrl())<a class="button button-primary" href="{{ $project->safeProjectUrl() }}" target="_blank" rel="noopener noreferrer">Visit Project</a>@endif</div></div></div></section>
@endsection
