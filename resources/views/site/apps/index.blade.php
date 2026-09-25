@extends('site.layouts.app')

@section('title', 'Our Apps | ASLAF TECH')
@section('meta_description', 'Explore published apps and digital products from ASLAF TECH.')

@section('content')
    <section class="page-banner"><div class="container"><span class="eyebrow">DIGITAL PRODUCTS</span><h1>Our Apps</h1><p>Explore apps and digital products created by ASLAF TECH.</p></div></section>
    <section class="section apps-list-section" aria-label="Published apps"><div class="container">
        @if($apps->total() === 0)
            <div class="public-empty"><span class="empty-graphic" aria-hidden="true">A</span><h2>Apps are on the way</h2><p>Published apps will appear here when they are ready to share.</p></div>
        @else
            <div class="apps-grid">
                @foreach($apps as $app)
                    @include('site.partials.app-card', ['app' => $app])
                @endforeach
            </div>
            @if($apps->hasPages())
                <nav class="pagination" aria-label="Apps pages"><span>Page {{ $apps->currentPage() }} of {{ $apps->lastPage() }}</span><div>@if($apps->previousPageUrl())<a href="{{ $apps->previousPageUrl() }}" rel="prev">Previous</a>@endif @if($apps->nextPageUrl())<a href="{{ $apps->nextPageUrl() }}" rel="next">Next</a>@endif</div></nav>
            @endif
        @endif
    </div></section>
@endsection
