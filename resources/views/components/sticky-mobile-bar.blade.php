@php($contact = config('clinic.contact'))
<div class="sticky-mobile-bar" data-sticky-bar aria-label="Quick actions">
    <a href="tel:{{ preg_replace('/[^+\d]/', '', $contact['mobile']) }}"><span aria-hidden="true">☎</span> Call</a>
    <a href="{{ $contact['messenger'] }}" rel="noopener noreferrer" target="_blank"><span aria-hidden="true">✉</span> Chat</a>
    <a class="sticky-book" href="{{ route('contact') }}#booking"><span aria-hidden="true">◈</span> Book</a>
</div>
