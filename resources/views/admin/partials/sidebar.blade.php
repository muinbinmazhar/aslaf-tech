<div class="sidebar-backdrop" data-sidebar-backdrop hidden></div>
<aside class="sidebar" id="admin-sidebar" aria-label="Admin navigation">
    <div class="sidebar-top">
        <a class="sidebar-brand" href="{{ route('admin.dashboard') }}" aria-label="ASLAF TECH Dashboard">
            <span class="brand-symbol" aria-hidden="true">A</span>
            <span>ASLAF <strong>TECH</strong><small>ADMIN PANEL</small></span>
        </a>
        <button class="sidebar-close" type="button" data-sidebar-close aria-label="Close menu"><span aria-hidden="true">&times;</span></button>
    </div>
    <nav class="sidebar-nav" aria-label="Main menu">
        <p class="nav-label">MENU</p>
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('admin.apps.*') ? 'active' : '' }}" href="{{ route('admin.apps.index') }}" @if(request()->routeIs('admin.apps.*')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Our Apps
        </a>
        <p class="nav-label website-nav-label">WEBSITE MANAGEMENT</p>
        <a class="nav-link {{ request()->routeIs('admin.website.settings.*') ? 'active' : '' }}" href="{{ route('admin.website.settings.edit') }}" @if(request()->routeIs('admin.website.settings.*')) aria-current="page" @endif>Website Settings</a>
        <a class="nav-link {{ request()->routeIs('admin.website.homepage.*') ? 'active' : '' }}" href="{{ route('admin.website.homepage.edit') }}" @if(request()->routeIs('admin.website.homepage.*')) aria-current="page" @endif>Homepage Content</a>
        <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}" @if(request()->routeIs('admin.services.*')) aria-current="page" @endif>Services</a>
        <a class="nav-link {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}" href="{{ route('admin.portfolio.index') }}" @if(request()->routeIs('admin.portfolio.*')) aria-current="page" @endif>Portfolio</a>
        <a class="nav-link {{ request()->routeIs('admin.contact-links.*') ? 'active' : '' }}" href="{{ route('admin.contact-links.index') }}" @if(request()->routeIs('admin.contact-links.*')) aria-current="page" @endif>Contact &amp; Social Links</a>
        <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}" @if(request()->routeIs('admin.messages.*')) aria-current="page" @endif>Contact Messages</a>
    </nav>
    <div class="sidebar-footer">ASLAF TECH <span>Admin Workspace</span></div>
</aside>
