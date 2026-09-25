@extends('admin.layouts.app')
@section('title', 'Homepage Content')
@section('content')
<div class="page-heading"><div><p class="eyebrow">WEBSITE MANAGEMENT</p><h1>Homepage Content</h1><p class="page-description">Edit section copy without changing the page layout.</p></div></div>
@include('admin.website.partials.alerts')
<form class="wm-form" method="POST" action="{{ route('admin.website.homepage.update') }}">@csrf @method('PUT')
    @foreach($content as $section => $fields)
        <section class="wm-panel"><h2>{{ ucwords(str_replace('_', ' ', $section)) }} Section</h2><div class="wm-grid">
            @foreach($fields as $field => $value)
                <div class="wm-field {{ $field === 'description' ? 'wide' : '' }}"><label for="{{ $section.'-'.$field }}">{{ ucwords(str_replace('_', ' ', $field)) }}@if(in_array($field, ['heading', 'eyebrow'])) * @endif</label>
                    @if($field === 'description')<textarea id="{{ $section.'-'.$field }}" name="content[{{ $section }}][{{ $field }}]" rows="3" @error("content.$section.$field") aria-invalid="true" @enderror>{{ old("content.$section.$field", $value) }}</textarea>@else<input id="{{ $section.'-'.$field }}" name="content[{{ $section }}][{{ $field }}]" value="{{ old("content.$section.$field", $value) }}" @if(in_array($field, ['heading', 'eyebrow'])) required @endif @error("content.$section.$field") aria-invalid="true" @enderror>@endif
                    @error("content.$section.$field")<span class="wm-error">{{ $message }}</span>@enderror
                </div>
            @endforeach
        </div></section>
    @endforeach
    <div class="wm-actions"><button class="wm-primary" type="submit">Save Homepage Content</button></div>
</form>
@endsection
