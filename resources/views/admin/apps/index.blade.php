@extends('admin.layouts.app')

@section('title', 'Our Apps')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-apps.css') }}?v={{ filemtime(public_path('css/admin-apps.css')) }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/admin-app-delete.js') }}" defer></script>
@endpush

@section('content')
    <div class="page-heading apps-heading">
        <div>
            <p class="eyebrow">CONTENT MANAGEMENT</p>
            <h1>Our Apps</h1>
            <p class="page-description">আপনার Apps-এর তালিকা ও প্রকাশের অবস্থা দেখুন।</p>
        </div>
        <a class="primary-button" href="{{ route('admin.apps.create') }}">+ Add New App</a>
    </div>

    @if(session('success'))
        <div class="notice-success" role="status">{{ session('success') }}</div>
    @endif

    @if($apps->isEmpty())
        <section class="empty-state" aria-labelledby="empty-title">
            <span class="empty-symbol" aria-hidden="true">A</span>
            <h2 id="empty-title">No Apps Yet</h2>
            <p>প্রথম App-এর তথ্য যোগ করে তালিকা শুরু করুন।</p>
            <a class="primary-button" href="{{ route('admin.apps.create') }}">Add New App</a>
        </section>
    @else
        <div class="table-card">
            <div class="table-scroll">
                <table class="apps-table">
                    <thead><tr><th scope="col">Thumbnail</th><th scope="col">App</th><th scope="col">Status</th><th scope="col">Sort Order</th><th scope="col">Created Date</th><th scope="col">Actions</th></tr></thead>
                    <tbody>
                        @foreach($apps as $app)
                            <tr>
                                <td>
                                    @if($app->logo_path)
                                        <img class="app-thumbnail-image" src="{{ asset('storage/'.$app->logo_path) }}" alt="{{ $app->name }} Thumbnail" width="96" height="72" loading="lazy">
                                    @else
                                        <span class="app-thumbnail-empty" aria-label="Thumbnail নেই">ছবি নেই</span>
                                    @endif
                                </td>
                                <td><strong>{{ $app->name }}</strong><br><small>Screenshots: {{ $app->screenshots_count }}</small></td>
                                <td><span class="status-badge {{ $app->is_published ? 'published' : 'draft' }}">{{ $app->is_published ? 'Published' : 'Draft' }}</span></td>
                                <td>{{ $app->sort_order }}</td>
                                <td><time datetime="{{ $app->created_at->toDateString() }}">{{ $app->created_at->format('d M Y') }}</time></td>
                                <td>
                                    <div class="app-actions">
                                        <a class="action-edit" href="{{ route('admin.apps.screenshots.index', $app) }}" aria-label="Manage screenshots for {{ $app->name }}">Manage Screenshots</a>
                                        <a class="action-edit" href="{{ route('admin.apps.edit', $app) }}" aria-label="Edit {{ $app->name }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.apps.destroy', $app) }}" data-confirm-delete data-app-name="{{ $app->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-delete" type="submit" aria-label="Delete {{ $app->name }}">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($apps->hasPages())
            <nav class="apps-pagination" aria-label="Apps pagination">
                <span>পৃষ্ঠা {{ $apps->currentPage() }} / {{ $apps->lastPage() }}</span>
                <div>
                    @if($apps->previousPageUrl())<a href="{{ $apps->previousPageUrl() }}" rel="prev">Previous</a>@endif
                    @if($apps->nextPageUrl())<a href="{{ $apps->nextPageUrl() }}" rel="next">Next</a>@endif
                </div>
            </nav>
        @endif
    @endif
@endsection
