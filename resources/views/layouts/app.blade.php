@php
    $clinic = config('clinic');
    $pageTitle = trim($__env->yieldContent('title', $clinic['name']));
    $description = trim($__env->yieldContent('description', $clinic['seo']['default_description']));
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => ['Dentist', 'LocalBusiness'],
        'name' => $clinic['name'],
        'url' => route('home'),
        'description' => $clinic['seo']['default_description'],
        'telephone' => $clinic['contact']['mobile'],
        'email' => $clinic['contact']['email'],
        'image' => url($clinic['seo']['og_image']),
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => $clinic['contact']['address'], 'addressCountry' => 'PH'],
        'openingHoursSpecification' => collect($clinic['seo']['opening_hours'])->map(fn ($hours) => ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $hours['days'], 'opens' => $hours['opens'], 'closes' => $hours['closes']])->all(),
    ];
    if ($clinic['contact']['geo']['latitude'] && $clinic['contact']['geo']['longitude']) {
        $schema['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => $clinic['contact']['geo']['latitude'], 'longitude' => $clinic['contact']['geo']['longitude']];
    }
@endphp
<!doctype html>
<html lang="en-PH" style="--clinic-primary: {{ $clinic['brand']['primary'] }}; --clinic-secondary: {{ $clinic['brand']['secondary'] }}; --clinic-accent: {{ $clinic['brand']['accent'] }}; --clinic-heading-font: {{ $clinic['brand']['font_heading'] }}; --clinic-body-font: {{ $clinic['brand']['font_body'] }};">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ $clinic['brand']['primary'] }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <meta name="description" content="{{ $description }}">
    @if($clinic['demo'])<meta name="robots" content="noindex, nofollow">@endif
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $clinic['name'] }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url($clinic['seo']['og_image']) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ url($clinic['seo']['og_image']) }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @if(request()->routeIs('home', 'about'))<link rel="preload" as="image" href="{{ $clinic['hero']['image'] }}" fetchpriority="high">@endif
    <title>{{ $pageTitle }}</title>
    <script type="application/ld+json">@json($schema)</script>
    @stack('schema')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream text-ink antialiased">
    <a class="skip-link" href="#main-content">Skip to content</a>
    @if ($clinic['demo'])
        <div class="demo-bar">Demo template · Replace sample clinic details and credentials before publishing</div>
    @endif
    <x-header />
    <main id="main-content">@yield('content')</main>
    <x-footer />
    <x-sticky-mobile-bar />
</body>
</html>
