@php
    $clinic = config('clinic');
    $previewOnly = (bool) ($clinic['booking']['preview_only'] ?? false);
    $externalAction = filled($clinic['booking']['form_action']);
    $action = $previewOnly ? '#booking' : ($externalAction ? $clinic['booking']['form_action'] : route('booking.store'));
@endphp
<form id="booking" class="booking-form" action="{{ $action }}" method="post" data-booking-form data-local="{{ $externalAction || $previewOnly ? 'false' : 'true' }}">
    @unless($externalAction || $previewOnly) @csrf @endunless
    <div class="form-heading"><span class="eyebrow">Appointment enquiry</span><h2>Let’s find a time for you.</h2><p>Share a few details and the clinic will confirm your appointment.</p></div>
    @if($previewOnly)<div class="form-feedback" role="status">This is a public template demo. Appointment requests are disabled until a clinic connects its own booking service.</div>@endif
    <fieldset class="booking-controls" @disabled($previewOnly)>
    @if(session('booking_success'))<div class="form-feedback success" role="status">{{ session('booking_success') }}</div>@endif
    @if($errors->any())<div class="form-feedback error" role="alert">Please check the highlighted fields and try again.</div>@endif
    <div class="form-grid">
        <div class="field"><label for="booking-name">Full name <span aria-hidden="true">*</span></label><input id="booking-name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="100" required aria-describedby="name-error"><small id="name-error" class="field-error">{{ $errors->first('name') }}</small></div>
        <div class="field"><label for="booking-mobile">Mobile number <span aria-hidden="true">*</span></label><input id="booking-mobile" name="mobile" type="tel" value="{{ old('mobile') }}" inputmode="tel" autocomplete="tel" placeholder="09XX XXX XXXX" required aria-describedby="mobile-error"><small id="mobile-error" class="field-error">{{ $errors->first('mobile') }}</small></div>
        <div class="field"><label for="booking-email">Email <span class="optional">Optional</span></label><input id="booking-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" aria-describedby="email-error"><small id="email-error" class="field-error">{{ $errors->first('email') }}</small></div>
        <div class="field"><label for="booking-service">Service <span aria-hidden="true">*</span></label><select id="booking-service" name="service" required aria-describedby="service-error"><option value="">Select a service</option>@foreach($clinic['services'] as $service)<option value="{{ $service['slug'] }}" @selected(old('service', request('service')) === $service['slug'])>{{ $service['name'] }}</option>@endforeach</select><small id="service-error" class="field-error">{{ $errors->first('service') }}</small></div>
        <div class="field"><label for="booking-dentist">Preferred dentist <span class="optional">Optional</span></label><select id="booking-dentist" name="dentist" aria-describedby="dentist-error"><option value="">No preference</option>@foreach($clinic['dentists'] as $dentist)<option value="{{ $dentist['slug'] }}" @selected(old('dentist', request('dentist')) === $dentist['slug'])>{{ $dentist['name'] }}</option>@endforeach</select><small id="dentist-error" class="field-error">{{ $errors->first('dentist') }}</small></div>
        <div class="field"><label for="booking-date">Preferred date <span aria-hidden="true">*</span></label><input id="booking-date" name="preferred_date" type="date" value="{{ old('preferred_date') }}" min="{{ now()->toDateString() }}" required aria-describedby="preferred_date-error"><small id="preferred_date-error" class="field-error">{{ $errors->first('preferred_date') }}</small></div>
        <div class="field"><label for="booking-time">Preferred time <span aria-hidden="true">*</span></label><select id="booking-time" name="time_slot" required aria-describedby="time_slot-error"><option value="">Select a time</option>@foreach($clinic['booking']['time_slots'] as $slot)<option value="{{ $slot }}" @selected(old('time_slot') === $slot)>{{ $slot }}</option>@endforeach</select><small id="time_slot-error" class="field-error">{{ $errors->first('time_slot') }}</small></div>
        <div class="field field-wide"><label for="booking-notes">Anything we should know? <span class="optional">Optional</span></label><textarea id="booking-notes" name="notes" rows="3" maxlength="500" placeholder="Scheduling preferences only — please avoid sharing medical details here." aria-describedby="notes-error">{{ old('notes') }}</textarea><small id="notes-error" class="field-error">{{ $errors->first('notes') }}</small></div>
    </div>
    <div class="honeypot" aria-hidden="true"><label for="booking-website">Leave this blank</label><input id="booking-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
    <label class="consent"><input type="checkbox" name="consent" value="1" @checked(old('consent')) required aria-describedby="consent-error"><span>I agree to be contacted about this appointment request and have read the <a href="{{ route('privacy') }}">privacy policy</a>.</span></label><small id="consent-error" class="field-error">{{ $errors->first('consent') }}</small>
    <div class="form-feedback" data-form-feedback role="status" aria-live="polite" hidden></div>
    <button class="button button-primary form-submit" type="submit">Send appointment request <span aria-hidden="true">↗</span></button>
    <p class="fine-print">This is an enquiry, not a confirmed booking. For urgent concerns, call {{ $clinic['contact']['emergency'] }}.</p>
    </fieldset>
</form>
