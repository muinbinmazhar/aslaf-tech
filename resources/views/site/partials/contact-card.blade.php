<article class="contact-card">
    <span class="contact-card-icon">@include('site.partials.contact-icon', ['icon' => \App\Support\ContactTypes::icon($contact->type, $contact->icon_key)])</span>
    <div class="contact-card-body">
        <p class="contact-card-type">{{ \App\Support\ContactTypes::label($contact->type) }}</p>
        <h3>{{ $contact->display_name }}</h3>
        @if($contact->public_url)
            <a class="contact-card-value" href="{{ $contact->public_url }}" @if(str_starts_with($contact->public_url, 'https://')) target="_blank" rel="noopener noreferrer" @endif>{{ $contact->value }}</a>
        @else
            <p class="contact-card-value">{{ $contact->value }}</p>
        @endif
        @if($contact->supporting_text)<p class="contact-card-note">{{ $contact->supporting_text }}</p>@endif
    </div>
</article>
