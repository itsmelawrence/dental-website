@extends('layouts.app')
@section('title', 'Our dentists | '.config('clinic.seo.title_suffix'))
@section('description', 'Meet the sample dental team at '.config('clinic.name').'.')
@section('content')
    <section class="page-heading"><div class="container-site"><span class="eyebrow">The people behind your care</span><h1>Get to know your dentists.</h1><p class="lead">A friendly hello can make the first visit feel much easier.</p></div></section>
    <section class="section-pad"><div class="container-site doctor-grid">@foreach(config('clinic.dentists') as $dentist)<x-doctor-card :dentist="$dentist" />@endforeach</div></section>
    <x-cta-section title="Have someone in mind?" text="Choose your preferred dentist in the appointment form and we’ll do our best to help." />
@endsection
