<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

#[Signature('site:export {url : Final root-domain URL for the static demo} {--output=dist : Output directory relative to the project root}')]
#[Description('Export public pages as static HTML for root-domain hosting')]
class ExportStaticSite extends Command
{
    public function handle(Kernel $kernel): int
    {
        $siteUrl = rtrim((string) $this->argument('url'), '/');
        $path = parse_url($siteUrl, PHP_URL_PATH);

        if (! filter_var($siteUrl, FILTER_VALIDATE_URL) || ($path !== null && $path !== '' && $path !== '/')) {
            $this->error('Provide a complete root-domain URL, such as https://demo.example.com.');

            return self::FAILURE;
        }

        if (blank(config('clinic.booking.form_action'))) {
            $this->error('Set clinic.booking.form_action to a working external form endpoint before exporting.');

            return self::FAILURE;
        }

        if (! File::exists(public_path('build/manifest.json'))) {
            $this->error('Run npm run build before exporting.');

            return self::FAILURE;
        }

        $output = base_path(trim((string) $this->option('output'), '/'));
        File::ensureDirectoryExists($output);
        File::copyDirectory(public_path('build'), $output.'/build');
        File::copyDirectory(public_path('images'), $output.'/images');
        File::copy(public_path('favicon.ico'), $output.'/favicon.ico');
        URL::useOrigin($siteUrl);
        URL::forceScheme(parse_url($siteUrl, PHP_URL_SCHEME));

        $paths = ['/', '/about', '/services', '/dentists', '/contact', '/privacy', '/sitemap.xml', '/robots.txt'];
        foreach (config('clinic.services') as $service) {
            $paths[] = '/services/'.$service['slug'];
        }

        foreach ($paths as $routePath) {
            $request = Request::create($siteUrl.$routePath, 'GET');
            $response = $kernel->handle($request);

            if ($response->getStatusCode() !== 200) {
                $this->error("Could not export {$routePath}: HTTP {$response->getStatusCode()}");

                return self::FAILURE;
            }

            $destination = $routePath === '/' ? 'index.html' : ltrim($routePath, '/');
            if (! str_ends_with($destination, '.xml') && ! str_ends_with($destination, '.txt')) {
                $destination .= '/index.html';
            }

            File::ensureDirectoryExists(dirname($output.'/'.$destination));
            File::put($output.'/'.$destination, $response->getContent());
            $kernel->terminate($request, $response);
        }

        $notFoundRequest = Request::create($siteUrl.'/404', 'GET');
        $notFoundResponse = $kernel->handle($notFoundRequest);
        if ($notFoundResponse->getStatusCode() !== 404) {
            $this->error('Could not export the branded 404 page.');

            return self::FAILURE;
        }
        File::put($output.'/404.html', $notFoundResponse->getContent());
        $kernel->terminate($notFoundRequest, $notFoundResponse);
        URL::useOrigin(null);
        URL::forceScheme(null);
        $this->info('Static site exported to '.$output);

        return self::SUCCESS;
    }
}
