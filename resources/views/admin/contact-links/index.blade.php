@extends('admin.layouts.app')
@section('title', 'Contact & Social Links')
@push('styles')<link rel="stylesheet" href="{{ asset('css/admin-contacts.css') }}?v={{ filemtime(public_path('css/admin-contacts.css')) }}">@endpush
@push('scripts')<script src="{{ asset('js/admin-contact-links.js') }}?v={{ filemtime(public_path('js/admin-contact-links.js')) }}" defer></script>@endpush
@section('content')
<div class="page-heading wm-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>Contact &amp; Social Links</h1><p class="page-description">Manage your contact details, social platforms and website visibility.</p></div><a class="wm-primary" href="{{ route('admin.contact-links.create') }}">Add Contact</a></div>
@include('admin.website.partials.alerts')
<form class="contact-filters" method="GET" action="{{ route('admin.contact-links.index') }}" role="search">
    <div><label for="contact-search">Search</label><input id="contact-search" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="Name or value"></div>
    <div><label for="contact-type-filter">Type Filter</label><select id="contact-type-filter" name="type"><option value="">All types</option>@foreach(\App\Support\ContactTypes::groups() as $group => $types)<optgroup label="{{ $group }}">@foreach($types as $key => [$label, $icon])<option value="{{ $key }}" @selected(($filters['type'] ?? '') === $key)>{{ $label }}</option>@endforeach</optgroup>@endforeach</select></div>
    <div><label for="contact-visibility-filter">Visibility Filter</label><select id="contact-visibility-filter" name="visibility"><option value="">All</option><option value="visible" @selected(($filters['visibility'] ?? '') === 'visible')>Visible</option><option value="hidden" @selected(($filters['visibility'] ?? '') === 'hidden')>Hidden</option></select></div>
    <button class="wm-primary" type="submit">Search</button>
</form>
@if($links->total() === 0)
    <div class="wm-empty contact-empty"><span class="contact-empty-icon">@include('site.partials.contact-icon', ['icon' => 'link'])</span><h2>No contacts found</h2><p>Add a contact or change your filters to see more results.</p><a class="wm-primary" href="{{ route('admin.contact-links.create') }}">Add Contact</a></div>
@else
    <p class="contact-sort-help">{{ $sortable ? 'Drag a handle to reorder this page. Use Move up or Move down to cross pages or use a keyboard.' : 'Clear search and filters to change contact order.' }}</p>
    <p class="contact-order-feedback" role="status" aria-live="polite" hidden data-order-feedback></p>
    <div class="contact-list" role="list" data-contact-list @if($sortable) data-reorder-url="{{ route('admin.contact-links.reorder') }}" data-csrf="{{ csrf_token() }}" data-page="{{ $links->currentPage() }}" @endif>
        @foreach($links as $contact)
            <article class="contact-row" role="listitem" data-contact-id="{{ $contact->id }}">
                <button class="contact-drag-handle" type="button" data-drag-handle aria-label="Drag {{ $contact->display_name }} to reorder" title="Drag to reorder" @disabled(! $sortable)><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="8" cy="5" r="1.6"/><circle cx="16" cy="5" r="1.6"/><circle cx="8" cy="12" r="1.6"/><circle cx="16" cy="12" r="1.6"/><circle cx="8" cy="19" r="1.6"/><circle cx="16" cy="19" r="1.6"/></svg></button>
                <div class="contact-row-main"><span class="contact-row-icon">@include('site.partials.contact-icon', ['icon' => \App\Support\ContactTypes::icon($contact->type, $contact->icon_key)])</span><div><strong>{{ $contact->display_name }}</strong><small>{{ \App\Support\ContactTypes::label($contact->type) }}</small></div></div>
                <div class="contact-row-value"><span class="contact-row-caption">Value</span><span class="contact-value-text" title="{{ $contact->value }}">{{ $contact->value }}</span><details class="contact-full-value"><summary>View full</summary><code>{{ $contact->value }}</code></details><button type="button" class="contact-copy" data-copy-value="{{ $contact->value }}" aria-label="Copy {{ $contact->display_name }} value" aria-live="polite">Copy</button></div>
                <div class="contact-row-locations"><span class="contact-row-caption">Locations</span>@if($contact->show_on_homepage)<span>Homepage</span>@endif @if($contact->show_in_footer)<span>Footer</span>@endif @if($contact->show_in_header)<span>Header</span>@endif</div>
                <div class="contact-row-status"><span class="contact-status {{ $contact->is_visible ? 'visible' : 'hidden' }}">{{ $contact->is_visible ? 'Visible' : 'Hidden' }}</span></div>
                <div class="contact-row-actions"><form method="POST" action="{{ route('admin.contact-links.move', $contact) }}">@csrf<input type="hidden" name="direction" value="up"><button class="contact-move" type="submit" aria-label="Move {{ $contact->display_name }} up" title="Move up" @disabled(! $sortable || ($loop->first && $links->currentPage() === 1))>↑</button></form><form method="POST" action="{{ route('admin.contact-links.move', $contact) }}">@csrf<input type="hidden" name="direction" value="down"><button class="contact-move" type="submit" aria-label="Move {{ $contact->display_name }} down" title="Move down" @disabled(! $sortable || ($loop->last && $links->currentPage() === $links->lastPage()))>↓</button></form><a href="{{ route('admin.contact-links.edit', $contact) }}">Edit</a><form method="POST" action="{{ route('admin.contact-links.toggle', $contact) }}">@csrf<button type="submit">{{ $contact->is_visible ? 'Hide' : 'Show' }}</button></form><form method="POST" action="{{ route('admin.contact-links.destroy', $contact) }}" data-confirm-delete>@csrf @method('DELETE')<button class="wm-danger" type="submit">Delete</button></form></div>
            </article>
        @endforeach
    </div>
    @if($links->hasPages())<div class="wm-pagination">{{ $links->links() }}</div>@endif
@endif
@endsection
