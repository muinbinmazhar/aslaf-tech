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
        @php($visualPath = app(\App\Support\WebsiteMedia::class)->heroVisualPath($content['hero']['visual_path']))
        @if($visualPath)
            <div class="hero-visual hero-visual-managed">
                @if($content['hero']['visual_show_rings'])
                    <div class="visual-orbit orbit-one" aria-hidden="true"></div>
                    <div class="visual-orbit orbit-two" aria-hidden="true"></div>
                @endif
                <div class="visual-core"><img class="visual-logo" src="{{ asset('storage/'.$visualPath) }}" alt="{{ $content['hero']['visual_alt'] ?? '' }}"></div>
                @if($content['hero']['visual_show_labels'])
                    @if(filled($content['hero']['visual_label_one']))<div class="visual-chip chip-one">{{ $content['hero']['visual_label_one'] }}</div>@endif
                    @if(filled($content['hero']['visual_label_two']))<div class="visual-chip chip-two">{{ $content['hero']['visual_label_two'] }}</div>@endif
                @endif
            </div>
        @endif
    </div>
</section>
