<div class="wm-field" data-media-group>
    <label for="{{ $field }}">{{ $label }}</label>
    <input id="{{ $field }}" type="file" name="{{ $field }}" accept="{{ $accept }}" data-media-input @error($field) aria-invalid="true" @enderror>
    <small>{{ $hint }}</small>
    @error($field)<span class="wm-error">{{ $message }}</span>@enderror
    <div class="wm-image-preview">
        <img data-media-image src="{{ $path ? asset('storage/'.$path) : '' }}" alt="{{ $label }} preview" @if(!$path) hidden @endif>
        <span data-media-placeholder @if($path) hidden @endif>No image selected</span>
    </div>
    @if($showRemove ?? false)
        <label class="wm-check"><input type="checkbox" name="remove_{{ $field }}" value="1" data-media-remove @checked(old('remove_'.$field, false))>Remove {{ $label }}</label>
    @endif
</div>
