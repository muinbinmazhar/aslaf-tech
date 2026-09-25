<section class="section apps-section" id="our-apps" aria-labelledby="home-apps-title">
    <div class="container">
        <div class="section-row"><div class="section-intro"><span class="eyebrow">{{ $content['apps']['eyebrow'] }}</span><h2 id="home-apps-title">{{ $content['apps']['heading'] }}</h2><p>{{ $content['apps']['description'] }}</p></div>@if($content['apps']['view_all_label'])<a class="text-arrow" href="{{ route('apps.index') }}">{{ $content['apps']['view_all_label'] }} <span aria-hidden="true">→</span></a>@endif</div>
        @if($featuredApps->isEmpty())
            <div class="public-empty"><span class="empty-graphic" aria-hidden="true">A</span><h3>Apps are on the way</h3><p>Published apps will appear here when they are ready to share.</p></div>
        @else
            <div class="apps-grid">
                @foreach($featuredApps as $app)
                    @include('site.partials.app-card', ['app' => $app])
                @endforeach
            </div>
        @endif
    </div>
</section>
