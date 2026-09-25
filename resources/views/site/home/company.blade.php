<section class="section portfolio-section" id="portfolio" aria-labelledby="portfolio-title">
    <div class="container">
        <div class="section-intro"><span class="eyebrow">{{ $content['portfolio']['eyebrow'] }}</span><h2 id="portfolio-title">{{ $content['portfolio']['heading'] }}</h2><p>{{ $content['portfolio']['description'] }}</p></div>
        @if($projects->isEmpty())
            <p class="portfolio-fallback">Project work will appear here when it is ready to share. <a class="text-arrow" href="#contact">Go to Contact <span aria-hidden="true">→</span></a></p>
        @else
            <div class="portfolio-grid">
                @foreach($projects as $project)
                    <article class="portfolio-card">
                        <a href="{{ route('portfolio.show', $project->slug) }}" class="portfolio-image" aria-label="View {{ $project->title }}">
                            @if($project->thumbnail_path)<img src="{{ asset('storage/'.$project->thumbnail_path) }}" alt="{{ $project->title }} thumbnail" loading="lazy">@else<span aria-hidden="true">AT</span>@endif
                        </a>
                        <div class="portfolio-card-body">@if($project->category)<span class="project-category">{{ $project->category }}</span>@endif<h3><a href="{{ route('portfolio.show', $project->slug) }}">{{ $project->title }}</a></h3>@if($project->short_description)<p>{{ $project->short_description }}</p>@endif @if($project->safeProjectUrl())<a class="text-arrow" href="{{ $project->safeProjectUrl() }}" target="_blank" rel="noopener noreferrer">Visit Project →</a>@endif</div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
<section class="section about-section" id="about" aria-labelledby="about-title">
    <div class="container about-layout"><div class="about-mark" aria-hidden="true">AT<span>TECH + DESIGN</span></div><div><span class="eyebrow">{{ $content['about']['eyebrow'] }}</span><h2 id="about-title">{{ $content['about']['heading'] }}</h2><p>{{ $content['about']['description'] }}</p></div></div>
</section>
