@extends('admin.layouts.app')

@section('title', 'Add New App')

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
            <h1>Add New App</h1>
            <p class="page-description">নতুন অ্যাপের তথ্য পূরণ করে সংরক্ষণ করুন।</p>
        </div>
        <a class="text-link" href="{{ route('admin.apps.index') }}">← Back to Apps</a>
    </div>

    @include('admin.apps.partials.form', ['app' => null])
@endsection
