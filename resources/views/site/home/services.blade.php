<section class="section services-section" id="services" aria-labelledby="services-title">
    <div class="container">
        <div class="section-intro"><span class="eyebrow">{{ $content['services']['eyebrow'] }}</span><h2 id="services-title">{{ $content['services']['heading'] }}</h2><p>{{ $content['services']['description'] }}</p></div>
        <div class="services-grid">
            @forelse($services as $service)
                <article class="service-card"><div class="service-card-top"><span class="service-icon" aria-hidden="true">{{ $service->iconSymbol() }}</span>@if($service->badge_text)<span class="service-badge">{{ $service->badge_text }}</span>@endif</div><h3>{{ $service->title }}</h3><p>{{ $service->short_description }}</p></article>
            @empty
                <div class="public-empty"><h3>Services are being updated</h3><p>Check back soon for more information.</p></div>
            @endforelse
        </div>
    </div>
</section>
