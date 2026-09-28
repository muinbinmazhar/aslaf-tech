@extends('admin.layouts.app')
@section('title', 'Homepage Content')
@section('content')
<div class="page-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>Homepage Content</h1><p class="page-description">Edit section copy without changing the page layout.</p></div></div>
@include('admin.website.partials.alerts')
<form class="wm-form" method="POST" enctype="multipart/form-data" data-hero-form action="{{ route('admin.website.homepage.update') }}">@csrf @method('PUT')
    @foreach($content as $section => $fields)
        <section class="wm-panel"><h2>{{ ucwords(str_replace('_', ' ', $section)) }} Section</h2><div class="wm-grid">
            @foreach($fields as $field => $value)
                @continue(str_starts_with($field, 'visual_'))
                <div class="wm-field {{ $field === 'description' ? 'wide' : '' }}"><label for="{{ $section.'-'.$field }}">{{ ucwords(str_replace('_', ' ', $field)) }}@if(in_array($field, ['heading', 'eyebrow'])) * @endif</label>
                    @if($field === 'description')<textarea id="{{ $section.'-'.$field }}" name="content[{{ $section }}][{{ $field }}]" rows="3" @error("content.$section.$field") aria-invalid="true" @enderror>{{ old("content.$section.$field", $value) }}</textarea>@else<input id="{{ $section.'-'.$field }}" name="content[{{ $section }}][{{ $field }}]" value="{{ old("content.$section.$field", $value) }}" @if(in_array($field, ['heading', 'eyebrow'])) required @endif @error("content.$section.$field") aria-invalid="true" @enderror>@endif
                    @error("content.$section.$field")<span class="wm-error">{{ $message }}</span>@enderror
                </div>
            @endforeach
        </div></section>
        @if($section === 'hero')
            @php($visualPath = app(\App\Support\WebsiteMedia::class)->heroVisualPath($fields['visual_path']))
            <section class="wm-panel hero-visual-panel" aria-labelledby="hero-visual-heading">
                <h2 id="hero-visual-heading">Hero Visual</h2>
                <div class="wm-grid">
                    <div class="wm-field wide" data-media-group>
                        <label for="hero_visual">Hero Image / Logo</label>
                        <div class="wm-image-preview">
                            <img data-media-image @if($visualPath) src="{{ asset('storage/'.$visualPath) }}" @else hidden @endif alt="Current hero visual preview">
                            <span data-media-placeholder @if($visualPath) hidden @endif>No hero visual uploaded.</span>
                        </div>
                        <label class="wm-primary hero-choose-button" for="hero_visual">{{ $visualPath ? 'Replace Image' : 'Choose Image' }}</label>
                        <input id="hero_visual" type="file" name="hero_visual" accept="image/jpeg,image/png,image/webp" data-media-input @error('hero_visual') aria-invalid="true" @enderror>
                        <small>Choose an image to upload or replace the current visual. Recommended: PNG / WebP with transparent background. JPG supported. Maximum 5 MB.</small>
                        @error('hero_visual')<span class="wm-error">{{ $message }}</span>@enderror
                        @if($fields['visual_path'])
                            <input type="checkbox" name="remove_hero_visual" value="1" data-media-remove hidden @checked(old('remove_hero_visual', false))>
                            <button type="button" class="wm-primary" data-hero-remove aria-pressed="{{ old('remove_hero_visual', false) ? 'true' : 'false' }}">{{ old('remove_hero_visual', false) ? 'Undo Remove' : 'Remove Image' }}</button>
                            <small>Removal is applied when you save.</small>
                        @endif
                    </div>
                    @foreach(['visual_alt' => 'Visual Alt Text', 'visual_label_one' => 'Floating Label 1', 'visual_label_two' => 'Floating Label 2'] as $field => $label)
                        <div class="wm-field {{ $field === 'visual_alt' ? 'wide' : '' }}">
                            <label for="hero-{{ $field }}">{{ $label }}</label>
                            <input id="hero-{{ $field }}" name="content[hero][{{ $field }}]" value="{{ old('content.hero.'.$field, $fields[$field]) }}" maxlength="{{ $field === 'visual_alt' ? 255 : 60 }}" @error("content.hero.$field") aria-invalid="true" @enderror>
                            @error("content.hero.$field")<span class="wm-error">{{ $message }}</span>@enderror
                        </div>
                    @endforeach
                    @foreach(['visual_show_rings' => 'Show Decorative Rings', 'visual_show_labels' => 'Show Floating Labels'] as $field => $label)
                        <div class="wm-field">
                            <input type="hidden" name="content[hero][{{ $field }}]" value="0">
                            <label class="wm-check"><input type="checkbox" name="content[hero][{{ $field }}]" value="1" @checked(old('content.hero.'.$field, $fields[$field]))>{{ $label }}</label>
                            @error("content.hero.$field")<span class="wm-error">{{ $message }}</span>@enderror
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach
    <div class="wm-actions"><button class="wm-primary" type="submit">Save Homepage Content</button></div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-website-media.js') }}" defer></script>
<script src="{{ asset('js/admin-homepage-hero.js') }}" defer></script>
@endpush