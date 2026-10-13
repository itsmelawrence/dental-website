@php($clinic = config('clinic'))
<footer class="site-footer">
    <div class="container-site footer-grid">
        <div class="footer-intro">
            <a class="brand brand-light" href="{{ route('home') }}"><span class="brand-mark" aria-hidden="true">✦</span><span class="brand-name">{{ $clinic['name'] }}</span></a>
            <p>{{ $clinic['tagline'] }}</p>
            <div class="footer-socials">
                @foreach ($clinic['social'] as $label => $link)
                    @if ($link)<a href="{{ $link }}" rel="noopener noreferrer" target="_blank">{{ ucfirst($label) }}</a>@endif
                @endforeach
            </div>
        </div>
        <div><h2>Explore</h2><a href="{{ route('about') }}">About us</a><a href="{{ route('services.index') }}">Services & prices</a><a href="{{ route('dentists') }}">Our dentists</a><a href="{{ route('contact') }}#booking">Book a visit</a></div>
        <div><h2>Visit us</h2><p>{{ $clinic['contact']['address'] }}</p><a href="mailto:{{ $clinic['contact']['email'] }}">{{ $clinic['contact']['email'] }}</a><a href="tel:{{ preg_replace('/[^+\d]/', '', $clinic['contact']['mobile']) }}">{{ $clinic['contact']['mobile'] }}</a></div>
        <div><h2>Opening hours</h2>@foreach ($clinic['hours'] as $day => $hours)<div class="hours-row"><span>{{ $day }}</span><span>{{ $hours }}</span></div>@endforeach</div>
    </div>
    <div class="container-site footer-bottom"><span>© {{ date('Y') }} {{ $clinic['name'] }}. @if($clinic['demo']) Demo content only. @endif</span><a href="{{ route('privacy') }}">Privacy policy</a></div>
</footer>
