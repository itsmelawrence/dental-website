<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home');
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function service(string $slug): View
    {
        $service = collect(config('clinic.services'))->firstWhere('slug', $slug);
        abort_if($service === null, 404);

        $related = collect(config('clinic.services'))->where('slug', '!=', $slug)->take(3)->all();

        return view('pages.service-detail', compact('service', 'related'));
    }

    public function dentists(): View
    {
        return view('pages.dentists');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function sitemap(): Response
    {
        $urls = [route('home'), route('about'), route('services.index'), route('dentists'), route('contact'), route('privacy')];

        foreach (config('clinic.services') as $service) {
            $urls[] = route('services.show', $service['slug']);
        }

        return response()->view('pages.sitemap', compact('urls'))->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        return response("User-agent: *\nAllow: /\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
