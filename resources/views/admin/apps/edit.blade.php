@extends('admin.layouts.app')

@section('title', 'Edit App')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-apps.css') }}?v={{ filemtime(public_path('css/admin-apps.css')) }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/admin-app-thumbnail.js') }}" defer></script>
@endpush

@section('content')
    <div class="page-heading apps-heading">
        <div>
            <p class="eyebrow">OUR APPS</p>
            <h1>Edit App</h1>
            <p class="page-description">অ্যাপের তথ্য পরিবর্তন করে সংরক্ষণ করুন।</p>
        </div>
        <div class="app-actions"><a class="text-link" href="{{ route('admin.apps.screenshots.index', $app) }}">Manage Screenshots</a><a class="text-link" href="{{ route('admin.apps.index') }}">← Back to Apps</a></div>
    </div>

    @include('admin.apps.partials.form', ['app' => $app])
@endsection
