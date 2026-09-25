<form class="app-form" method="POST" action="{{ $app ? route('admin.apps.update', $app) : route('admin.apps.store') }}" enctype="multipart/form-data">
    @csrf
    @if($app)
        @method('PUT')
    @endif

    @if($errors->any())
        <div class="form-errors" role="alert">কিছু তথ্য সংশোধন করতে হবে। নিচে চিহ্নিত ফিল্ডগুলো দেখুন।</div>
    @endif

    <div class="form-grid">
        <div class="field">
            <label for="name">Name <span aria-hidden="true">*</span></label>
            <input id="name" name="name" type="text" value="{{ old('name', $app?->name) }}" maxlength="255" required aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" @error('name') aria-describedby="name-error" @enderror>
            @error('name')<p class="field-error" id="name-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="slug">Slug</label>
            <input id="slug" name="slug" type="text" value="{{ old('slug', $app?->slug) }}" maxlength="255" placeholder="খালি রাখলে Name থেকে তৈরি হবে" aria-invalid="{{ $errors->has('slug') ? 'true' : 'false' }}" @error('slug') aria-describedby="slug-error" @enderror>
            <p class="field-hint">ইংরেজি ছোট অক্ষর, সংখ্যা ও হাইফেন ব্যবহার করুন।</p>
            @error('slug')<p class="field-error" id="slug-error">{{ $message }}</p>@enderror
        </div>
        <div class="field full">
            <label for="short_description">Short Description</label>
            <textarea id="short_description" name="short_description" rows="3" aria-invalid="{{ $errors->has('short_description') ? 'true' : 'false' }}" @error('short_description') aria-describedby="short_description-error" @enderror>{{ old('short_description', $app?->short_description) }}</textarea>
            @error('short_description')<p class="field-error" id="short_description-error">{{ $message }}</p>@enderror
        </div>
        <div class="field full">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="6" aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}" @error('description') aria-describedby="description-error" @enderror>{{ old('description', $app?->description) }}</textarea>
            @error('description')<p class="field-error" id="description-error">{{ $message }}</p>@enderror
        </div>
        <div class="field full">
            <label for="logo">App Thumbnail</label>
            <input id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp" data-thumbnail-input aria-invalid="{{ $errors->has('logo') ? 'true' : 'false' }}" aria-describedby="logo-hint logo-safe-area{{ $errors->has('logo') ? ' logo-error' : '' }}">
            <p class="field-hint" id="logo-hint">Recommended portrait size: 1080 × 1908 px (3:5.3 ratio). JPG, PNG or WebP · Max 2 MB.</p>
            <p class="field-hint" id="logo-safe-area">এটি পরামর্শমাত্র; অন্য অনুপাতের ছবিও আপলোড করা যাবে।</p>
            @error('logo')<p class="field-error" id="logo-error">{{ $message }}</p>@enderror
            <div class="thumbnail-preview" data-thumbnail-preview aria-label="Thumbnail preview">
                <img data-thumbnail-image src="{{ $app && $app->logo_path ? asset('storage/'.$app->logo_path) : '' }}" alt="App Thumbnail preview" @if(! $app || ! $app->logo_path) hidden @endif>
                <span class="thumbnail-placeholder" data-thumbnail-placeholder @if($app && $app->logo_path) hidden @endif>Thumbnail preview</span>
            </div>
            @if($app)
                <label class="checkbox-label remove-thumbnail" for="remove_thumbnail">
                    <input id="remove_thumbnail" name="remove_thumbnail" type="checkbox" value="1" data-remove-thumbnail @checked(old('remove_thumbnail', false))>
                    <span>Remove Thumbnail</span>
                </label>
                <p class="field-hint">নতুন ছবি না দিলে বর্তমান Thumbnail অপরিবর্তিত থাকবে।</p>
                @error('remove_thumbnail')<p class="field-error">{{ $message }}</p>@enderror
            @endif
        </div>
        <div class="field">
            <label for="android_url">Android URL</label>
            <input id="android_url" name="android_url" type="url" value="{{ old('android_url', $app?->android_url) }}" maxlength="255" placeholder="https://" aria-invalid="{{ $errors->has('android_url') ? 'true' : 'false' }}" @error('android_url') aria-describedby="android_url-error" @enderror>
            @error('android_url')<p class="field-error" id="android_url-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="ios_url">iOS URL</label>
            <input id="ios_url" name="ios_url" type="url" value="{{ old('ios_url', $app?->ios_url) }}" maxlength="255" placeholder="https://" aria-invalid="{{ $errors->has('ios_url') ? 'true' : 'false' }}" @error('ios_url') aria-describedby="ios_url-error" @enderror>
            @error('ios_url')<p class="field-error" id="ios_url-error">{{ $message }}</p>@enderror
        </div>
        <div class="field full">
            <label for="web_url">Web URL</label>
            <input id="web_url" name="web_url" type="url" value="{{ old('web_url', $app?->web_url) }}" maxlength="255" placeholder="https://" aria-invalid="{{ $errors->has('web_url') ? 'true' : 'false' }}" @error('web_url') aria-describedby="web_url-error" @enderror>
            @error('web_url')<p class="field-error" id="web_url-error">{{ $message }}</p>@enderror
        </div>
        <div class="field full">
            <label for="youtube_url">YouTube Video URL</label>
            <input id="youtube_url" name="youtube_url" type="url" value="{{ old('youtube_url', $app?->youtube_url) }}" maxlength="255" placeholder="https://www.youtube.com/watch?v=..." aria-invalid="{{ $errors->has('youtube_url') ? 'true' : 'false' }}" aria-describedby="youtube_url-hint{{ $errors->has('youtube_url') ? ' youtube_url-error' : '' }}">
            <p class="field-hint" id="youtube_url-hint">Optional. Paste a YouTube video link; leave blank to hide the video.</p>
            @error('youtube_url')<p class="field-error" id="youtube_url-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label for="sort_order">Sort Order</label>
            <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $app?->sort_order ?? 0) }}" aria-invalid="{{ $errors->has('sort_order') ? 'true' : 'false' }}" @error('sort_order') aria-describedby="sort_order-error" @enderror>
            @error('sort_order')<p class="field-error" id="sort_order-error">{{ $message }}</p>@enderror
        </div>
        <div class="field publish-field">
            <input type="hidden" name="is_published" value="0">
            <label class="checkbox-label" for="is_published"><input id="is_published" name="is_published" type="checkbox" value="1" @checked(old('is_published', $app?->is_published ?? false))><span>Published</span></label>
            <p class="field-hint">চিহ্নিত না করলে Draft হিসেবে সংরক্ষিত হবে।</p>
            @error('is_published')<p class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-actions">
        <a class="text-link" href="{{ route('admin.apps.index') }}">Cancel</a>
        <button class="primary-button" type="submit">{{ $app ? 'Update App' : 'Save App' }}</button>
    </div>
</form>
