@extends('layouts.app')
@section('title', $service['name'].' | '.config('clinic.seo.title_suffix'))
@section('description', $service['short'])
@php
    $faqSchema = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => collect($service['faqs'])->map(fn ($faq) => ['@type' => 'Question', 'name' => $faq['question'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']]])->all()];
@endphp
@push('schema')<script type="application/ld+json">@json($faqSchema)</script>@endpush
@section('content')
    <section class="page-heading detail-heading"><div class="container-site"><a class="back-link" href="{{ route('services.index') }}">← All services</a><span class="eyebrow">Dental services</span><h1>{{ $service['name'] }}</h1><p class="lead">{{ $service['short'] }}</p><div class="detail-hero-bottom"><strong>Starting at ₱{{ number_format($service['price_from']) }}</strong><a class="button button-primary" href="{{ route('contact', ['service' => $service['slug']]) }}#booking">Enquire about this service ↗</a></div></div></section>
    <section class="section-pad"><div class="container-site detail-grid"><div><span class="eyebrow">Overview</span><h2>A clear path forward.</h2><p class="section-lead">{{ $service['long'] }}</p><h3>Who it’s for</h3><p>{{ $service['for'] }}</p></div><aside class="detail-facts"><div><span>Appointment length</span><strong>{{ $service['duration'] }}</strong></div><div><span>Visits</span><strong>{{ $service['visits'] }}</strong></div><div><span>Starting price</span><strong>₱{{ number_format($service['price_from']) }}</strong></div></aside></div></section>
    <section class="section-pad why-section"><div class="container-site"><div class="section-heading"><div><span class="eyebrow">What to expect</span><h2>Your treatment journey.</h2></div></div><div class="steps-grid">@foreach($service['procedure_steps'] as $index => $step)<div><span>0{{ $index + 1 }}</span><h3>{{ $step }}</h3></div>@endforeach</div></div></section>
    <section class="section-pad"><div class="container-site story-grid"><div><span class="eyebrow">After your visit</span><h2>Looking after your result.</h2></div><p class="section-lead">{{ $service['aftercare'] }}</p></div></section>
    <section class="section-pad faq-section"><div class="container-site faq-layout"><div><span class="eyebrow">Common questions</span><h2>Good to know.</h2></div><x-faq :items="$service['faqs']" /></div></section>
    <section class="section-pad"><div class="container-site"><div class="section-heading"><div><span class="eyebrow">Keep exploring</span><h2>Other ways we can help.</h2></div></div><div class="service-grid">@foreach($related as $item)<x-service-card :service="$item" />@endforeach</div></div></section>
    <x-cta-section title="Let’s talk about your options." />
@endsection
