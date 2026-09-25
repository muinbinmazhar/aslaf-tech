@extends('admin.layouts.app')
@section('title', 'Portfolio')
@section('content')
<div class="page-heading wm-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>Portfolio</h1><p class="page-description">Publish real projects when they are ready to share.</p></div><a class="wm-primary" href="{{ route('admin.portfolio.create') }}">Add Project</a></div>
@include('admin.website.partials.alerts')
@if($projects->total() === 0)<div class="wm-empty">No projects yet. <a href="{{ route('admin.portfolio.create') }}">Add Project</a></div>@else
<div class="wm-table-scroll"><table class="wm-table"><thead><tr><th>Project</th><th>Category</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@foreach($projects as $project)<tr><td><strong>{{ $project->title }}</strong></td><td>{{ $project->category ?: '—' }}</td><td>{{ $project->sort_order }}</td><td>{{ $project->is_published ? 'Published' : 'Draft' }}</td><td><div class="wm-row-actions"><a href="{{ route('admin.portfolio.edit', $project) }}">Edit</a><form method="POST" action="{{ route('admin.portfolio.toggle', $project) }}">@csrf<button type="submit">{{ $project->is_published ? 'Unpublish' : 'Publish' }}</button></form><form method="POST" action="{{ route('admin.portfolio.destroy', $project) }}" data-confirm-delete>@csrf @method('DELETE')<button class="wm-danger" type="submit">Delete</button></form></div></td></tr>@endforeach
</tbody></table></div>
@if($projects->hasPages())<div class="wm-pagination">{{ $projects->links() }}</div>@endif
@endif
@push('scripts')<script src="{{ asset('js/admin-website-confirm.js') }}" defer></script>@endpush
@endsection
