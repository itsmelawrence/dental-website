<?php

namespace Tests\Feature;

use Tests\TestCase;

class SitePagesTest extends TestCase
{
    public function test_public_pages_render_with_clinic_content(): void
    {
        $pages = ['/', '/about', '/services', '/services/cleaning', '/dentists', '/contact', '/privacy'];

        foreach ($pages as $page) {
            $this->get($page)->assertOk()->assertSee('Hiraya Dental Studio');
        }
    }

    public function test_unknown_service_uses_branded_404(): void
    {
        $this->get('/services/unknown-service')->assertNotFound()->assertSee('That page wandered off.');
    }

    public function test_sitemap_contains_service_pages(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertSee('/services/cleaning');
    }

    public function test_clinic_config_changes_visible_brand_and_price(): void
    {
        config()->set('clinic.name', 'New Demo Dental');
        config()->set('clinic.brand.primary', '#123456');
        $services = config('clinic.services');
        $services[0]['price_from'] = 3210;
        config()->set('clinic.services', $services);

        $this->get('/')->assertOk()->assertSee('New Demo Dental')->assertSee('--clinic-primary: #123456')->assertSee('₱3,210');
    }
}
