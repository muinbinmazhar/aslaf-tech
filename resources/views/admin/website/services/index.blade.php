@extends('admin.layouts.app')
@section('title', 'Services')
@section('content')
<div class="page-heading wm-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>Services</h1><p class="page-description">Manage service cards shown on the Homepage.</p></div><a class="wm-primary" href="{{ route('admin.services.create') }}">Add Service</a></div>
@include('admin.website.partials.alerts')
@if($services->total() === 0)<div class="wm-empty">No services yet. <a href="{{ route('admin.services.create') }}">Add Service</a></div>@else
<div class="wm-table-scroll"><table class="wm-table"><thead><tr><th>Service</th><th>Icon</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@foreach($services as $service)<tr><td><strong>{{ $service->title }}</strong></td><td>{{ $service->iconSymbol() }}</td><td>{{ $service->sort_order }}</td><td>{{ $service->is_published ? 'Published' : 'Draft' }}</td><td><div class="wm-row-actions"><a href="{{ route('admin.services.edit', $service) }}">Edit</a><form method="POST" action="{{ route('admin.services.toggle', $service) }}">@csrf<button type="submit">{{ $service->is_published ? 'Unpublish' : 'Publish' }}</button></form><form method="POST" action="{{ route('admin.services.destroy', $service) }}" data-confirm-delete>@csrf @method('DELETE')<button class="wm-danger" type="submit">Delete</button></form></div></td></tr>@endforeach
</tbody></table></div>
@if($services->hasPages())<div class="wm-pagination">{{ $services->links() }}</div>@endif
@endif
@push('scripts')<script src="{{ asset('js/admin-website-confirm.js') }}" defer></script>@endpush
@endsection
