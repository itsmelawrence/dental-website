@extends('layouts.app')
@section('title', 'Dental services & prices | '.config('clinic.seo.title_suffix'))
@section('description', 'Explore treatments and indicative starting prices at '.config('clinic.name').'.')
@section('content')
    <section class="page-heading"><div class="container-site"><span class="eyebrow">Treatments & transparent pricing</span><h1>Explore care for your smile.</h1><p class="lead">From routine cleaning to more involved treatment, we’ll help you understand your options.</p></div></section>
    <section class="section-pad"><div class="container-site"><div class="service-grid">@foreach(config('clinic.services') as $service)<x-service-card :service="$service" />@endforeach</div></div></section>
    <section class="section-pad payment-section"><div class="container-site price-layout"><div><span class="eyebrow">At a glance</span><h2>Starting prices, upfront.</h2><p class="section-lead">Every smile is different. Your dentist will explain the final cost after an assessment.</p></div><x-price-table :services="config('clinic.services')" /></div></section>
    <x-cta-section title="Unsure where to begin?" text="Tell us what’s on your mind and we’ll help you choose a starting point." />
@endsection
