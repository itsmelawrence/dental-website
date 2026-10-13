@php($hero = config('clinic.hero'))
<section class="hero-section">
    <div class="container-site hero-grid">
        <div class="hero-copy">
            <span class="eyebrow"><span class="eyebrow-line"></span>{{ $hero['eyebrow'] }}</span>
            <h1>{{ $hero['headline'] }}</h1>
            <p class="lead">{{ $hero['description'] }}</p>
            <div class="hero-actions"><a class="button button-primary" href="{{ route('contact') }}#booking">Book an appointment <span aria-hidden="true">↗</span></a><a class="text-link" href="{{ config('clinic.contact.messenger') }}" rel="noopener noreferrer" target="_blank">Chat on Messenger <span aria-hidden="true">↗</span></a></div>
            <p class="hero-note"><span aria-hidden="true">✳</span> A comfortable first step starts here.</p>
        </div>
        <div class="hero-visual"><img src="{{ $hero['image'] }}" alt="{{ $hero['image_alt'] }}" width="1800" height="1013" fetchpriority="high"><div class="hero-float"><span class="float-icon" aria-hidden="true">✦</span><span><strong>Care with clarity</strong><small>We make room for every question.</small></span></div></div>
    </div>
</section>
