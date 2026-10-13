<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

#[Signature('site:export {url : Final URL for the static site} {--output=dist : Output directory relative to the project root} {--demo : Disable appointment submissions in the exported demo}')]
#[Description('Export public pages as static HTML')]
class ExportStaticSite extends Command
{
    public function handle(Kernel $kernel): int
    {
        $siteUrl = rtrim((string) $this->argument('url'), '/');
        $urlParts = parse_url($siteUrl);

        if (! filter_var($siteUrl, FILTER_VALIDATE_URL) || ! in_array($urlParts['scheme'] ?? null, ['http', 'https'], true) || isset($urlParts['query']) || isset($urlParts['fragment'])) {
            $this->error('Provide a complete site URL, such as https://example.github.io/my-site.');

            return self::FAILURE;
        }

        if (! $this->option('demo') && blank(config('clinic.booking.form_action'))) {
            $this->error('Set clinic.booking.form_action to a working external form endpoint, or export with --demo.');

            return self::FAILURE;
        }

        if (! File::exists(public_path('build/manifest.json'))) {
            $this->error('Run npm run build before exporting.');

            return self::FAILURE;
        }

        $siteOrigin = ($urlParts['scheme']).'://'.$urlParts['host'].(isset($urlParts['port']) ? ':'.$urlParts['port'] : '');
        $basePath = rtrim($urlParts['path'] ?? '', '/');
        $output = base_path(trim((string) $this->option('output'), '/'));
        File::ensureDirectoryExists($output);
        File::copyDirectory(public_path('build'), $output.'/build');
        File::copyDirectory(public_path('images'), $output.'/images');
        File::copy(public_path('favicon.ico'), $output.'/favicon.ico');
        File::put($output.'/.nojekyll', '');
        URL::useOrigin($siteOrigin);
        URL::forceScheme($urlParts['scheme']);
        $previousPreviewOnly = config('clinic.booking.preview_only');
        config()->set('clinic.booking.preview_only', (bool) $this->option('demo'));

        $paths = ['/', '/about', '/services', '/dentists', '/contact', '/privacy', '/sitemap.xml', '/robots.txt'];
        foreach (config('clinic.services') as $service) {
            $paths[] = '/services/'.$service['slug'];
        }

        foreach ($paths as $routePath) {
            $request = Request::create($siteOrigin.$routePath, 'GET');
            $response = $kernel->handle($request);

            if ($response->getStatusCode() !== 200) {
                $this->error("Could not export {$routePath}: HTTP {$response->getStatusCode()}");

                return self::FAILURE;
            }

            $destination = $routePath === '/' ? 'index.html' : ltrim($routePath, '/');
            if (! str_ends_with($destination, '.html') && ! str_ends_with($destination, '.xml') && ! str_ends_with($destination, '.txt')) {
                $destination .= '/index.html';
            }

            File::ensureDirectoryExists(dirname($output.'/'.$destination));
            File::put($output.'/'.$destination, $this->forSitePath($response->getContent(), $siteOrigin, $siteUrl, $basePath));
            $kernel->terminate($request, $response);
        }

        $notFoundRequest = Request::create($siteOrigin.'/404', 'GET');
        $notFoundResponse = $kernel->handle($notFoundRequest);
        if ($notFoundResponse->getStatusCode() !== 404) {
            $this->error('Could not export the branded 404 page.');

            return self::FAILURE;
        }
        File::put($output.'/404.html', $this->forSitePath($notFoundResponse->getContent(), $siteOrigin, $siteUrl, $basePath));
        $kernel->terminate($notFoundRequest, $notFoundResponse);
        URL::useOrigin(null);
        URL::forceScheme(null);
        config()->set('clinic.booking.preview_only', $previousPreviewOnly);
        $this->info('Static site exported to '.$output);

        return self::SUCCESS;
    }

    private function forSitePath(string $content, string $siteOrigin, string $siteUrl, string $basePath): string
    {
        if ($basePath === '') {
            return $content;
        }

        $content = str_replace($siteOrigin, $siteUrl, $content);

        return str_replace(['="/images/', '="/build/'], ['="'.$basePath.'/images/', '="'.$basePath.'/build/'], $content);
    }
}
