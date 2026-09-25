<header class="topbar">
    <div class="topbar-start">
        <button class="menu-toggle" type="button" data-sidebar-open aria-controls="admin-sidebar" aria-expanded="false" aria-label="Open menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="topbar-title"><strong>ASLAF TECH</strong><span>Admin Dashboard</span></div>
    </div>
    <div class="topbar-actions">
        <div class="admin-identity"><span class="admin-avatar" aria-hidden="true">{{ mb_substr(auth()->user()->name, 0, 1) }}</span><span class="admin-name">{{ auth()->user()->name }}<small>Administrator</small></span></div>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="logout-button" type="submit">Logout</button></form>
    </div>
</header>
