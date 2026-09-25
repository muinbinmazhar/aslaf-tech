<footer class="site-footer">
    <div class="container footer-grid">
        <div><a class="footer-brand" href="{{ route('home') }}">@if($settings->logo_path)<img class="footer-logo" src="{{ asset('storage/'.$settings->logo_path) }}" alt="">@endif{{ $settings->website_name }}</a><p>{{ $settings->footer_description ?: 'Technology, software and creative design for purposeful digital experiences.' }}</p></div>
        <nav aria-label="Footer navigation"><h2>Explore</h2><a href="{{ route('home') }}">Home</a><a href="{{ route('home') }}#services">Services</a><a href="{{ route('apps.index') }}">Our Apps</a><a href="{{ route('home') }}#portfolio">Portfolio</a></nav>
        <nav aria-label="Company navigation"><h2>Company</h2><a href="{{ route('home') }}#about">About</a><a href="{{ route('home') }}#contact">Contact</a></nav>
    </div>
    @php($footerGroups = \App\Support\ContactTypes::grouped($footerContacts))
    @if($footerGroups)
        <div class="container footer-contact-groups">
            @foreach($footerGroups as $group => $contacts)
                <section class="footer-contact-group" aria-label="{{ $group }}">
                    <h2>{{ $group }}</h2>
                    <div class="footer-contact-links">
                        @foreach($contacts as $contact)
                            @if(in_array($contact->type, ['mobile', 'whatsapp', 'email'], true))
                                <div class="footer-contact footer-contact-copyable">
                                    @include('site.partials.contact-icon', ['icon' => \App\Support\ContactTypes::icon($contact->type, $contact->icon_key)])
                                    <div class="footer-contact-details">
                                        <span class="footer-contact-name">{{ $contact->display_name }}</span>
                                        @if($contact->public_url)
                                            <a href="{{ $contact->public_url }}" class="footer-contact-value" @if(str_starts_with($contact->public_url, 'https://')) target="_blank" rel="noopener noreferrer" @endif>{{ $contact->value }}</a>
                                        @else
                                            <span class="footer-contact-value">{{ $contact->value }}</span>
                                        @endif
                                    </div>
                                    <button type="button" class="footer-copy-button" data-footer-copy="{{ $contact->value }}" aria-label="Copy {{ $contact->display_name }}" aria-live="polite">Copy</button>
                                </div>
                            @elseif($contact->public_url)
                                <a class="footer-contact" href="{{ $contact->public_url }}" aria-label="{{ $contact->display_name }}: {{ $contact->value }}" @if(str_starts_with($contact->public_url, 'https://')) target="_blank" rel="noopener noreferrer" @endif>@include('site.partials.contact-icon', ['icon' => \App\Support\ContactTypes::icon($contact->type, $contact->icon_key)])<span>{{ $contact->display_name }}</span></a>
                            @else
                                <span class="footer-contact">@include('site.partials.contact-icon', ['icon' => \App\Support\ContactTypes::icon($contact->type, $contact->icon_key)])<span>{{ $contact->display_name }}: {{ $contact->value }}</span></span>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif
    <div class="container footer-bottom">© {{ date('Y') }} {{ $settings->copyright_text ?: 'ASLAF TECH. All rights reserved.' }}</div>
</footer>
