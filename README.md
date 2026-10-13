# Dental clinic website template

A reusable Laravel 13 and Blade website for Philippine dental clinics. The included **Hiraya Dental Studio** is fictional demo content. Replace its details, images, prices, claims, and privacy notice before any public launch.

## Quick start

Requirements: PHP 8.3+, Composer, Node.js, and npm. No database is needed for the website or its local booking flow.

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan serve
```

Open the URL printed by `php artisan serve` (normally `http://127.0.0.1:8000`). For development with live frontend updates, run `npm run dev` in a second terminal. Run `php artisan test` for the feature suite.

The default mail driver is `log`, so local appointment enquiries appear in `storage/logs/laravel.log` rather than being delivered. Session and cache use files; the queue uses `sync`.

## Re-skin for a clinic

Edit **only [`config/clinic.php`](config/clinic.php)** for clinic content and branding. It contains the name, logo, colours and font stacks, contact and chat links, hours and branches, credentials, statistics, hero and story copy, services and prices, dentists, HMO and payment details, testimonials, FAQs, gallery, booking, privacy, and SEO values. The visible design reads those values at render time.

1. Set `demo` to `false` after replacing the demo content.
2. Add a clinic logo and real, consented photos under `public/images`, then update the image paths and alt text. The bundled hero photograph is generated demo imagery; dentist portraits and before/after results are intentionally empty.
3. Replace every sample phone number, email address, chat link, branch, hours, starting price, dentist profile, and `SAMPLE-PRC` licence value. Set `credentials.pda_member` only when verified. Add HMO names only after confirming actual acceptance.
4. Set `brand.primary`, `brand.secondary`, `brand.accent`, `brand.font_heading`, and `brand.font_body`. CSS theme tokens map to these values; choose colour combinations with WCAG AA contrast.
5. Review the privacy notice and set its contact and actual retention period. Have the clinic review it before launch. The [National Privacy Commission](https://privacy.gov.ph/data-privacy-act-/) explains the information and data-subject rights covered by RA 10173.
6. Check every page, treatment description, medical claim, price, licence, image consent, and contact link with the clinic before publishing.

The contact `messenger` value should be an `https://m.me/your-page` URL, `viber` a `viber://chat?number=%2B63...` link, and `whatsapp` an `https://wa.me/63...` URL. Use the clinic's own verified accounts. The default demo links are examples only.

## Appointment handling

### Laravel-hosted site

Leave `booking.form_action` empty. The form posts to `POST /book`, which validates required fields, Philippine mobile format, future date, chosen service and dentist, consent, and a honeypot. The route is limited to five requests per minute. A valid request sends a `BookingEnquiry` email synchronously to `booking.recipient_email`, falling back to `contact.email`. No enquiry is saved to the database. Notes are limited to scheduling details; the form asks visitors not to include medical information.

For live delivery, configure a working Laravel mail transport in `.env` and a clinic-owned recipient email in `config/clinic.php`. Confirm deliverability, mailbox security, the message content, and the clinic's response process before launch. A successful form response means the mail transport accepted the message; the clinic must still confirm the appointment.

### Static demo

Set `booking.form_action` to a working third-party form URL. The form then uses a normal HTML POST so it works without Laravel. For example, [Formspree](https://formspree.io/plans/) currently offers a free testing/development tier with a monthly submission limit; use the clinic's own form ID and check the provider's current terms, data handling, spam controls, and limits. The provider controls the static form's success page and server-side spam protection. The Laravel route's rate limit does not apply to static hosting.

Build and export for a **root-domain** static host:

```bash
npm run build
php artisan site:export https://demo.example.com
```

The command writes `dist/` with HTML for every page, treatment page, `404.html`, `sitemap.xml`, `robots.txt`, the built CSS/JS, and images. Upload the contents of `dist/` to a static host that serves directory `index.html` files. The exporter refuses to run without an external form endpoint so it cannot silently publish a broken booking form. Run it with the final domain URL because page links, canonicals, and the sitemap are absolute. Re-export after changing clinic config or assets. This export targets root-domain hosting; a subdirectory such as a default GitHub Pages repository URL needs URL rewriting or a custom domain.

## Deployment and template use

- Host the Laravel app with the web root pointed at `public/`, `APP_DEBUG=false`, a unique `APP_KEY`, writable `storage/` and `bootstrap/cache/`, a working mail transport, and HTTPS. Run `composer install --no-dev --optimize-autoloader` and `npm run build` during deployment.
- The project does not require database migrations for v1. Laravel's unused starter database files remain available if a future clinic needs them.
- Set the repository as a **GitHub template repository** in GitHub settings after creating and pushing a repository. Each clinic can then start from the template and edit its `config/clinic.php`.
- Validate desktop and mobile rendering, keyboard navigation, links, form delivery, and accessibility with actual clinic data before launch.

## Structure

The website uses `resources/views/layouts/app.blade.php`, reusable components under `resources/views/components/`, pages under `resources/views/pages/`, and small vanilla JS modules under `resources/js/`. Tailwind CSS 4 and the clinic theme tokens live in `resources/css/app.css`; Vite builds the assets. `PageController` serves public pages, sitemap, and robots; `BookingController` handles local enquiries. `ExportStaticSite` prepares the static demo.

Direct PHP packages are `laravel/framework` and `laravel/tinker`; development packages are `fakerphp/faker`, `laravel/boost`, `laravel/pail`, `laravel/pao`, `laravel/pint`, `mockery/mockery`, `nunomaduro/collision`, and `phpunit/phpunit`. Direct npm development packages are `@tailwindcss/vite`, `tailwindcss`, `vite`, `laravel-vite-plugin`, and `concurrently`; `@laravel/multiplex` is optional in the Laravel scaffold. Exact resolved versions are pinned in `composer.lock` and `package-lock.json`.
