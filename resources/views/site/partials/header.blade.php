<header class="site-header">
    <div class="container header-inner">
        <a class="site-brand" href="{{ route('home') }}" aria-label="{{ $settings->website_name }} Home">
            @if($settings->logo_path)<img class="brand-logo" src="{{ asset('storage/'.$settings->logo_path) }}" alt="">@else<span class="brand-mark" aria-hidden="true">A</span>@endif
            <span>{{ $settings->website_name }}<small>{{ $settings->tagline ?: 'BUILDING DIGITAL POSSIBILITIES' }}</small></span>
        </a>
        <button class="menu-button" type="button" aria-controls="site-nav" aria-expanded="false" aria-label="Open menu" data-menu-toggle><span></span><span></span><span></span></button>
        <nav id="site-nav" class="site-nav" aria-label="Main navigation" data-site-nav>
            <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
            <a href="{{ route('home') }}#services">Services</a>
            <div class="nav-dropdown" data-dropdown>
                <button type="button" aria-expanded="false" aria-controls="apps-nav-menu" data-dropdown-toggle class="{{ request()->routeIs('apps.*') ? 'nav-active' : '' }}">Our Apps <span aria-hidden="true">⌄</span></button>
                <div id="apps-nav-menu" class="dropdown-panel" hidden data-dropdown-panel>
                    <a href="{{ route('apps.index') }}" @if(request()->routeIs('apps.index')) aria-current="page" @endif>All Apps</a>
                    @foreach($menuApps as $menuApp)
                        <a href="{{ route('apps.show', $menuApp->slug) }}" @if(request()->routeIs('apps.show') && request()->route('slug') === $menuApp->slug) aria-current="page" @endif>{{ $menuApp->name }}</a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('home') }}#portfolio">Portfolio</a>
            <a href="{{ route('home') }}#about">About</a>
            @if($headerContacts->isNotEmpty())
                <div class="nav-dropdown contact-nav-dropdown" data-contact-dropdown>
                    <button type="button" aria-expanded="false" aria-controls="contact-nav-menu" data-contact-toggle>Quick Contact <span aria-hidden="true">⌄</span></button>
                    <div id="contact-nav-menu" class="dropdown-panel contact-dropdown-panel" hidden data-contact-panel>
                        @foreach($headerContacts as $contact)
                            @if($contact->public_url)
                                <a href="{{ $contact->public_url }}" title="{{ $contact->value }}" @if(str_starts_with($contact->public_url, 'https://')) target="_blank" rel="noopener noreferrer" @endif>@include('site.partials.contact-icon', ['icon' => \App\Support\ContactTypes::icon($contact->type, $contact->icon_key)])<span>{{ $contact->display_name }}</span></a>
                            @else
                                <span class="contact-dropdown-text">@include('site.partials.contact-icon', ['icon' => \App\Support\ContactTypes::icon($contact->type, $contact->icon_key)])<span>{{ $contact->display_name }}: {{ $contact->value }}</span></span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            <a class="nav-contact" href="{{ route('home') }}#contact">Contact</a>
        </nav>
    </div>
</header>
