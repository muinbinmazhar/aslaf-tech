<article class="app-card">
    <a class="app-card-image" href="{{ route('apps.show', $app->slug) }}" aria-label="View {{ $app->name }} details">
        @if($app->logo_path)
            <img src="{{ asset('storage/'.$app->logo_path) }}" alt="{{ $app->name }} thumbnail" loading="lazy">
        @else
            <span class="app-image-placeholder" aria-hidden="true"><span>AT</span></span>
        @endif
    </a>
    <div class="app-card-body">
        <h3><a href="{{ route('apps.show', $app->slug) }}">{{ $app->name }}</a></h3>
        @if($app->short_description)<p>{{ $app->short_description }}</p>@endif
        @include('site.partials.download-links', ['destinations' => $app->destinations()])
    </div>
</article>
