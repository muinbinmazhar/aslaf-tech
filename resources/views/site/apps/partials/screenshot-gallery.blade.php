@if($app->screenshots->isNotEmpty())
    <section class="app-gallery" aria-labelledby="app-gallery-title" data-app-gallery>
        <div class="section-intro"><span class="eyebrow">A CLOSER LOOK</span><h2 id="app-gallery-title">App Screenshots</h2></div>
        <div class="app-gallery-track" aria-label="{{ $app->name }} screenshots">
            @foreach($app->screenshots as $screenshot)
                <button class="app-gallery-item" type="button" data-gallery-item data-src="{{ Storage::disk('public')->url($screenshot->file_path) }}" data-alt="{{ $app->name }} screenshot {{ $loop->iteration }}" aria-label="View screenshot {{ $loop->iteration }} of {{ $app->screenshots->count() }}">
                    <img src="{{ Storage::disk('public')->url($screenshot->file_path) }}" alt="{{ $app->name }} screenshot {{ $loop->iteration }}" loading="lazy" decoding="async">
                </button>
            @endforeach
        </div>
        <dialog class="app-gallery-dialog" aria-label="Screenshot preview" data-gallery-dialog>
            <div class="app-gallery-dialog-top"><span data-gallery-position></span><button type="button" data-gallery-close aria-label="Close screenshot preview">Close ✕</button></div>
            <div class="app-gallery-stage"><button type="button" data-gallery-previous aria-label="Previous screenshot">‹</button><img data-gallery-image alt=""><button type="button" data-gallery-next aria-label="Next screenshot">›</button></div>
        </dialog>
    </section>
@endif
