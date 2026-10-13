@extends('layouts.app')
@section('title', 'Contact & appointments | '.config('clinic.seo.title_suffix'))
@section('description', 'Request an appointment or reach '.config('clinic.name').' through phone or chat.')
@section('content')
    <section class="page-heading"><div class="container-site"><span class="eyebrow">We’d love to hear from you</span><h1>Let’s plan your visit.</h1><p class="lead">Book through the form or reach out in the way that feels easiest.</p></div></section>
    <section class="section-pad"><div class="container-site contact-grid">
        <x-booking-form />
        <aside class="contact-aside">
            <div class="contact-card">
                <span class="eyebrow">Prefer a conversation?</span><h2>Say hello.</h2>
                <a href="tel:{{ preg_replace('/[^+\d]/', '', config('clinic.contact.mobile')) }}">{{ config('clinic.contact.mobile') }} ↗</a>
                <a href="mailto:{{ config('clinic.contact.email') }}">{{ config('clinic.contact.email') }} ↗</a>
                <div class="chat-links"><a href="{{ config('clinic.contact.messenger') }}" target="_blank" rel="noopener noreferrer">Messenger ↗</a><a href="{{ config('clinic.contact.viber') }}">Viber ↗</a><a href="{{ config('clinic.contact.whatsapp') }}" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a></div>
            </div>
            <div class="contact-card">
                <span class="eyebrow">Find us</span>
                @foreach(config('clinic.branches') as $branch)
                    <div class="branch"><h2>{{ $branch['name'] }}</h2><p>{{ $branch['address'] }}<br>{{ $branch['phone'] }} · {{ $branch['hours'] }}</p>@if($branch['map_link'])<a href="{{ $branch['map_link'] }}" target="_blank" rel="noopener noreferrer">Get directions ↗</a>@endif</div>
                @endforeach
                @if(config('clinic.contact.map_embed'))<iframe src="{{ config('clinic.contact.map_embed') }}" title="Clinic location map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>@else<div class="map-placeholder">Map placeholder<br><small>Add a verified map embed in clinic config.</small></div>@endif
            </div>
            <div class="contact-card"><span class="eyebrow">Opening hours</span>@foreach(config('clinic.hours') as $day => $hours)<div class="hours-row"><span>{{ $day }}</span><strong>{{ $hours }}</strong></div>@endforeach</div>
            <div class="contact-card emergency"><strong>Urgent dental concern?</strong><p>Call the clinic for advice. For a medical emergency, contact local emergency services.</p><a href="tel:{{ preg_replace('/[^+\d]/', '', config('clinic.contact.emergency')) }}">{{ config('clinic.contact.emergency') }} ↗</a></div>
        </aside>
    </div></section>
@endsection
