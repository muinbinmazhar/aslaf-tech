@extends('admin.layouts.app')
@section('title', 'Contact Messages')
@section('content')
<div class="page-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>Contact Messages</h1><p class="page-description">Messages submitted through the public contact form.</p></div></div>
@include('admin.website.partials.alerts')
@if($messages->total() === 0)<div class="wm-empty">No messages yet.</div>@else
<div class="wm-table-scroll"><table class="wm-table"><thead><tr><th>Status</th><th>Sender</th><th>Phone</th><th>Service</th><th>Subject</th><th>Message</th><th>Date</th><th>Action</th></tr></thead><tbody>
@foreach($messages as $message)<tr><td>{{ $message->is_read ? 'Read' : 'Unread' }}</td><td>{{ $message->name }}<br><small>{{ $message->email }}</small></td><td>{{ $message->phone ?: '—' }}</td><td>{{ $message->service_interest ?: '—' }}</td><td>{{ $message->subject }}</td><td class="wm-message-excerpt">{{ $message->message }}</td><td>{{ $message->created_at->format('d M Y') }}</td><td><a href="{{ route('admin.messages.show', $message) }}">View</a></td></tr>@endforeach
</tbody></table></div>
@if($messages->hasPages())<div class="wm-pagination">{{ $messages->links() }}</div>@endif
@endif
@endsection
