<section class="hero" aria-labelledby="hero-title">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">{{ $content['hero']['eyebrow'] }}</span>
            <h1 id="hero-title">{{ $content['hero']['heading'] }}</h1>
            <p>{{ $content['hero']['description'] }}</p>
            <div class="hero-actions">
                @if($content['hero']['primary_label'] && $content['hero']['primary_link'])<a class="button button-primary" href="{{ $content['hero']['primary_link'] }}">{{ $content['hero']['primary_label'] }} <span aria-hidden="true">↗</span></a>@endif
                @if($content['hero']['secondary_label'] && $content['hero']['secondary_link'])<a class="button button-outline" href="{{ $content['hero']['secondary_link'] }}">{{ $content['hero']['secondary_label'] }}</a>@endif
            </div>
        </div>
        <div class="hero-visual" aria-hidden="true"><div class="visual-orbit orbit-one"></div><div class="visual-orbit orbit-two"></div><div class="visual-core">@if($settings->logo_path)<img class="visual-logo" src="{{ asset('storage/'.$settings->logo_path) }}" alt="">@else<span class="visual-monogram">A</span><span>{{ $settings->website_name }}</span><small>DESIGN × DEVELOPMENT</small>@endif</div><div class="visual-chip chip-one">01 / DESIGN</div><div class="visual-chip chip-two">02 / BUILD</div></div>
    </div>
</section>
