@extends('admin.layouts.app')
@section('title', 'View Message')
@section('content')
<div class="page-heading"><div><p class="eyebrow">CONTACT MESSAGES</p><h1>View Message</h1></div><a class="wm-link" href="{{ route('admin.messages.index') }}">Back to Messages</a></div>
@include('admin.website.partials.alerts')
<article class="wm-panel wm-message"><h2>{{ $message->subject }}</h2><dl><dt>Sender Name</dt><dd>{{ $message->name }}</dd><dt>Email</dt><dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd><dt>Phone</dt><dd>{{ $message->phone ?: '—' }}</dd><dt>Service of Interest</dt><dd>{{ $message->service_interest ?: '—' }}</dd><dt>Submission Date</dt><dd>{{ $message->created_at->format('d M Y H:i') }}</dd><dt>Status</dt><dd>{{ $message->is_read ? 'Read' : 'Unread' }}</dd></dl><h3>Message</h3><p class="wm-message-body">{{ $message->message }}</p></article>
<div class="wm-row-actions wm-message-actions"><form method="POST" action="{{ route('admin.messages.toggle', $message) }}">@csrf<button class="wm-primary" type="submit">Mark as {{ $message->is_read ? 'Unread' : 'Read' }}</button></form><form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm-delete>@csrf @method('DELETE')<button class="wm-danger" type="submit">Delete Message</button></form></div>
@push('scripts')<script src="{{ asset('js/admin-website-confirm.js') }}" defer></script>@endpush
@endsection
