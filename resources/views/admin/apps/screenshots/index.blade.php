@extends('admin.layouts.app')

@section('title', 'Manage Screenshots')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-apps.css') }}?v={{ filemtime(public_path('css/admin-apps.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-app-screenshots.css') }}?v={{ filemtime(public_path('css/admin-app-screenshots.css')) }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/admin-app-screenshots.js') }}?v={{ filemtime(public_path('js/admin-app-screenshots.js')) }}" defer></script>
@endpush

@section('content')
    <div class="page-heading screenshot-heading">
        <div><p class="eyebrow">OUR APPS</p><h1>Manage Screenshots</h1><p class="page-description">Upload, arrange and manage screenshots for this app.</p></div>
        <div class="screenshot-header-links"><a class="text-link" href="{{ route('admin.apps.index') }}">← Back to Apps</a><a class="text-link" href="{{ route('admin.apps.edit', $app) }}">Edit App</a></div>
    </div>

    <div class="screenshot-app-summary">
        @if($app->logo_path)<img src="{{ Storage::disk('public')->url($app->logo_path) }}" alt="" width="64" height="64">@endif
        <div><strong>{{ $app->name }}</strong><span>{{ $app->screenshots->count() }} / 30 screenshots</span></div>
    </div>

    @if(session('success'))<div class="notice-success" role="status">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <section class="screenshot-panel" aria-labelledby="upload-heading">
        <h2 id="upload-heading">Upload Screenshots</h2>
        <form method="POST" action="{{ route('admin.apps.screenshots.store', $app) }}" enctype="multipart/form-data" data-screenshot-upload>
            @csrf
            <label class="screenshot-file-label" for="screenshots">Choose Images</label>
            <input id="screenshots" name="screenshots[]" type="file" accept="image/jpeg,image/png,image/webp" multiple required aria-describedby="screenshot-help screenshot-selection">
            <p id="screenshot-selection" role="status" data-selection-count>No images selected</p>
            <p id="screenshot-help" class="field-hint">JPG, PNG or WebP.<br>Recommended portrait size: 1080 × 1908 px (3:5.3 ratio).<br>Up to 10 files per upload, 5 MB each.<br>Maximum 30 screenshots per app.</p>
            <div class="screenshot-upload-actions"><button class="primary-button" type="submit">Upload Screenshots</button><button class="screenshot-secondary" type="button" data-clear-selection>Clear Selection</button></div>
        </form>
    </section>

    @if($app->screenshots->isNotEmpty())
        <section class="screenshot-panel" aria-labelledby="current-heading">
            <h2 id="current-heading">Current Screenshots</h2>
            <p class="field-hint">Use the move buttons to save a new order.</p>
            <form id="screenshot-order" method="POST" action="{{ route('admin.apps.screenshots.reorder', $app) }}" data-screenshot-order>@csrf
                @foreach($app->screenshots as $screenshot)<input type="hidden" name="screenshot_ids[]" value="{{ $screenshot->id }}">@endforeach
            </form>
            <div class="screenshot-grid">
                @foreach($app->screenshots as $screenshot)
                    <article class="screenshot-card">
                        <img src="{{ Storage::disk('public')->url($screenshot->file_path) }}" alt="{{ $app->name }} screenshot {{ $loop->iteration }}" loading="lazy" decoding="async">
                        <div class="screenshot-card-actions">
                            <span>Position {{ $loop->iteration }}</span>
                            <div class="screenshot-controls">
                                <button type="button" class="screenshot-secondary" data-move="-1" data-id="{{ $screenshot->id }}" aria-label="Move screenshot {{ $loop->iteration }} earlier" @disabled($loop->first)>←</button>
                                <button type="button" class="screenshot-secondary" data-move="1" data-id="{{ $screenshot->id }}" aria-label="Move screenshot {{ $loop->iteration }} later" @disabled($loop->last)>→</button>
                                <form method="POST" action="{{ route('admin.apps.screenshots.destroy', [$app, $screenshot]) }}" data-confirm-screenshot-delete>@csrf @method('DELETE')<button type="submit" class="screenshot-delete">Delete</button></form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
