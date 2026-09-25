@extends('admin.layouts.app')
@section('title', $project ? 'Edit Project' : 'Add Project')
@push('scripts')<script src="{{ asset('js/admin-website-media.js') }}" defer></script>@endpush
@section('content')
<div class="page-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>{{ $project ? 'Edit Project' : 'Add Project' }}</h1></div><a class="wm-link" href="{{ route('admin.portfolio.index') }}">Back to Portfolio</a></div>
@include('admin.website.partials.alerts')
<form class="wm-form wm-panel" method="POST" action="{{ $project ? route('admin.portfolio.update', $project) : route('admin.portfolio.store') }}" enctype="multipart/form-data">@csrf @if($project)@method('PUT')@endif
<div class="wm-grid">
    <div class="wm-field"><label for="title">Project Title *</label><input id="title" name="title" required value="{{ old('title', $project?->title) }}" @error('title') aria-invalid="true" @enderror>@error('title')<span class="wm-error">{{ $message }}</span>@enderror</div>
    <div class="wm-field"><label for="slug">Slug</label><input id="slug" name="slug" value="{{ old('slug', $project?->slug) }}" placeholder="Generated from title if blank" @error('slug') aria-invalid="true" @enderror>@error('slug')<span class="wm-error">{{ $message }}</span>@enderror</div>
    <div class="wm-field wide"><label for="short_description">Short Description</label><textarea id="short_description" name="short_description" rows="3" @error('short_description') aria-invalid="true" @enderror>{{ old('short_description', $project?->short_description) }}</textarea>@error('short_description')<span class="wm-error">{{ $message }}</span>@enderror</div>
    <div class="wm-field wide"><label for="description">Project Description</label><textarea id="description" name="description" rows="6" @error('description') aria-invalid="true" @enderror>{{ old('description', $project?->description) }}</textarea>@error('description')<span class="wm-error">{{ $message }}</span>@enderror</div>
    @include('admin.website.partials.media-field', ['field' => 'thumbnail', 'label' => 'Project Thumbnail', 'path' => $project?->thumbnail_path, 'accept' => 'image/jpeg,image/png,image/webp', 'hint' => 'JPG, PNG or WebP · Max 2 MB.', 'showRemove' => (bool) $project])
    <div class="wm-field"><label for="category">Project Category</label><input id="category" name="category" value="{{ old('category', $project?->category) }}" @error('category') aria-invalid="true" @enderror>@error('category')<span class="wm-error">{{ $message }}</span>@enderror</div>
    <div class="wm-field"><label for="project_url">Project URL</label><input id="project_url" name="project_url" type="url" value="{{ old('project_url', $project?->project_url) }}" @error('project_url') aria-invalid="true" @enderror>@error('project_url')<span class="wm-error">{{ $message }}</span>@enderror</div>
    <div class="wm-field"><label for="sort_order">Sort Order *</label><input id="sort_order" name="sort_order" type="number" min="0" required value="{{ old('sort_order', $project?->sort_order ?? 0) }}" @error('sort_order') aria-invalid="true" @enderror>@error('sort_order')<span class="wm-error">{{ $message }}</span>@enderror</div>
    <div class="wm-field"><input type="hidden" name="is_published" value="0"><label class="wm-check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $project?->is_published ?? false))>Published</label>@error('is_published')<span class="wm-error">{{ $message }}</span>@enderror</div>
</div><div class="wm-actions"><a class="wm-link" href="{{ route('admin.portfolio.index') }}">Cancel</a><button class="wm-primary" type="submit">{{ $project ? 'Update Project' : 'Save Project' }}</button></div></form>
@endsection
