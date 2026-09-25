<section class="section contact-section" id="contact" aria-labelledby="contact-title">
    <div class="container contact-panel">
        <div class="contact-intro"><span class="eyebrow">{{ $content['contact']['eyebrow'] }}</span><h2 id="contact-title">{{ $content['contact']['heading'] }}</h2><p>{{ $content['contact']['description'] }}</p>
            @if($homeContacts->isNotEmpty())
                <div class="contact-card-grid">
                    @foreach($homeContacts as $contact)
                        @include('site.partials.contact-card', ['contact' => $contact])
                    @endforeach
                </div>
            @endif
        </div>
        <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
            @csrf
            @if(session('contact_success'))<p class="contact-alert success" role="status">{{ session('contact_success') }}</p>@endif
            @if($errors->any())<p class="contact-alert error" role="alert">Please review the highlighted fields.</p>@endif
            <div class="contact-fields">
                <div><label for="contact-name">Full Name *</label><input id="contact-name" name="name" value="{{ old('name') }}" required maxlength="150" @error('name') aria-invalid="true" @enderror>@error('name')<small>{{ $message }}</small>@enderror</div>
                <div><label for="contact-email">Email Address *</label><input id="contact-email" type="email" name="email" value="{{ old('email') }}" required maxlength="255" @error('email') aria-invalid="true" @enderror>@error('email')<small>{{ $message }}</small>@enderror</div>
                <div><label for="contact-phone">Phone Number</label><input id="contact-phone" name="phone" value="{{ old('phone') }}" maxlength="30" @error('phone') aria-invalid="true" @enderror>@error('phone')<small>{{ $message }}</small>@enderror</div>
                <div><label for="contact-service">Service of Interest</label><input id="contact-service" name="service_interest" value="{{ old('service_interest') }}" maxlength="150" @error('service_interest') aria-invalid="true" @enderror>@error('service_interest')<small>{{ $message }}</small>@enderror</div>
                <div class="full"><label for="contact-subject">Subject *</label><input id="contact-subject" name="subject" value="{{ old('subject') }}" required maxlength="200" @error('subject') aria-invalid="true" @enderror>@error('subject')<small>{{ $message }}</small>@enderror</div>
                <div class="full"><label for="contact-message">Message *</label><textarea id="contact-message" name="message" rows="5" required maxlength="5000" @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>@error('message')<small>{{ $message }}</small>@enderror</div>
            </div>
            <div class="honeypot" aria-hidden="true"><label for="contact-company">Company</label><input id="contact-company" name="company" tabindex="-1" autocomplete="off"></div>
            <button class="button button-primary" type="submit">Send Message</button>
        </form>
    </div>
</section>
