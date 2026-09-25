@extends('admin.layouts.app')
@section('title', $contact ? 'Edit Contact' : 'Add Contact')
@push('styles')<link rel="stylesheet" href="{{ asset('css/admin-contacts.css') }}?v={{ filemtime(public_path('css/admin-contacts.css')) }}">@endpush
@push('scripts')<script src="{{ asset('js/admin-contact-links.js') }}" defer></script>@endpush
@section('content')
<div class="page-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>{{ $contact ? 'Edit Contact' : 'Add Contact' }}</h1><p class="page-description">Choose where this contact appears on the public website.</p></div><a class="wm-link" href="{{ route('admin.contact-links.index') }}">Back to Contacts</a></div>
@include('admin.website.partials.alerts')
<form class="wm-form wm-panel contact-form-admin" method="POST" action="{{ $contact ? route('admin.contact-links.update', $contact) : route('admin.contact-links.store') }}">@csrf @if($contact)@method('PUT')@endif
    @include('admin.contact-links.partials.form')
</form>
@endsection
