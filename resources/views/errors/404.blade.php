@extends('layouts.app')
@section('title', 'Page not found | '.config('clinic.seo.title_suffix'))
@section('content')<section class="not-found"><div class="container-site"><span class="eyebrow">404 · Page not found</span><h1>That page wandered off.</h1><p class="lead">Let’s get you back to a place that can help.</p><div class="hero-actions"><a class="button button-primary" href="{{ route('home') }}">Back to home ↗</a><a class="button button-outline" href="{{ route('contact') }}#booking">Book a visit</a></div></div></section>@endsection
