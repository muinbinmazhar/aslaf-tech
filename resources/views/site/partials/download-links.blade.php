@if(count($destinations) === 1)
    <a class="button button-primary download-button" href="{{ $destinations[0]['url'] }}" target="_blank" rel="noopener noreferrer">{{ $destinations[0]['label'] }}</a>
@elseif(count($destinations) > 1)
    <div class="download-menu" data-download-menu>
        <button class="button button-primary download-button" type="button" aria-expanded="false" data-download-toggle>Download <span aria-hidden="true">⌄</span></button>
        <div class="download-panel" hidden data-download-panel>
            @foreach($destinations as $destination)
                <a href="{{ $destination['url'] }}" target="_blank" rel="noopener noreferrer">{{ $destination['label'] }}</a>
            @endforeach
        </div>
    </div>
@else
    <span class="download-unavailable">Download unavailable</span>
@endif
