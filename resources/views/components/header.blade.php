@php($clinic = config('clinic'))
<header class="site-header" data-header>
    <div class="container-site header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ $clinic['name'] }} home">
            @if ($clinic['logo'])
                <img src="{{ $clinic['logo'] }}" alt="{{ $clinic['name'] }}" width="160" height="56">
            @else
                <span class="brand-mark" aria-hidden="true">✦</span><span class="brand-name">{{ $clinic['name'] }}</span>
            @endif
        </a>
        <nav class="desktop-nav" aria-label="Primary navigation">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
            <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>About</a>
            <a href="{{ route('services.index') }}" @class(['active' => request()->routeIs('services.*')])>Services</a>
            <a href="{{ route('dentists') }}" @class(['active' => request()->routeIs('dentists')])>Our dentists</a>
            <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>Contact</a>
        </nav>
        <div class="header-actions">
            <a class="button button-primary header-book" href="{{ route('contact') }}#booking">Book an appointment <span aria-hidden="true">↗</span></a>
            <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false" data-menu-toggle><span></span><span></span><span></span></button>
        </div>
    </div>
    <nav id="mobile-menu" class="mobile-nav" aria-label="Mobile navigation" hidden data-mobile-menu>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('services.index') }}">Services</a>
        <a href="{{ route('dentists') }}">Our dentists</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a class="button button-primary" href="{{ route('contact') }}#booking">Book an appointment</a>
    </nav>
</header>
