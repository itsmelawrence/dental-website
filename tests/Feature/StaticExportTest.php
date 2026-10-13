<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class StaticExportTest extends TestCase
{
    public function test_export_requires_external_form_endpoint(): void
    {
        $exitCode = Artisan::call('site:export', ['url' => 'https://demo.example.com', '--output' => 'dist/test-export']);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('form_action', Artisan::output());
    }

    public function test_export_writes_pages_and_assets_for_static_host(): void
    {
        config()->set('clinic.booking.form_action', 'https://formspree.io/f/example');
        $output = base_path('dist/test-export');

        try {
            $exitCode = Artisan::call('site:export', ['url' => 'https://demo.example.com', '--output' => 'dist/test-export']);

            $this->assertSame(0, $exitCode, Artisan::output());
            $this->assertFileExists($output.'/index.html');
            $this->assertFileExists($output.'/services/cleaning/index.html');
            $this->assertFileExists($output.'/sitemap.xml');
            $this->assertFileExists($output.'/build/manifest.json');
            $this->assertStringContainsString('https://formspree.io/f/example', File::get($output.'/contact/index.html'));
            $this->assertStringContainsString('https://demo.example.com/services/cleaning', File::get($output.'/sitemap.xml'));
        } finally {
            File::deleteDirectory($output);
        }
    }

    public function test_demo_export_supports_github_pages_subdirectory_without_accepting_bookings(): void
    {
        $output = base_path('dist/test-export');

        try {
            $exitCode = Artisan::call('site:export', ['url' => 'https://example.github.io/dental-website', '--output' => 'dist/test-export', '--demo' => true]);

            $this->assertSame(0, $exitCode, Artisan::output());
            $this->assertFileExists($output.'/.nojekyll');
            $this->assertStringContainsString('href="https://example.github.io/dental-website"', File::get($output.'/index.html'));
            $this->assertStringContainsString('href="https://example.github.io/dental-website/about"', File::get($output.'/index.html'));
            $this->assertStringContainsString('src="/dental-website/images/demo-consultation.jpg"', File::get($output.'/index.html'));
            $this->assertStringContainsString('https://example.github.io/dental-website/services/cleaning', File::get($output.'/sitemap.xml'));
            $this->assertStringContainsString('Appointment requests are disabled', File::get($output.'/contact/index.html'));
            $this->assertStringContainsString('<fieldset class="booking-controls" disabled>', File::get($output.'/contact/index.html'));
        } finally {
            File::deleteDirectory($output);
        }
    }
}
