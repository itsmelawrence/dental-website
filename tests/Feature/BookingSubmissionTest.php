<?php

namespace Tests\Feature;

use App\Mail\BookingEnquiry;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingSubmissionTest extends TestCase
{
    public function test_valid_enquiry_sends_email_and_returns_confirmation(): void
    {
        Mail::fake();

        $response = $this->postJson('/book', $this->validBooking());

        $response->assertOk()->assertJsonPath('message', 'Thank you. Your appointment request has been sent. The clinic will confirm availability with you.');
        Mail::assertSent(BookingEnquiry::class, function (BookingEnquiry $mail) {
            return $mail->hasTo('hello@example.com')
                && $mail->booking['name'] === 'Sample Patient'
                && $mail->booking['mobile'] === '09171234567';
        });
    }

    public function test_invalid_mobile_and_past_date_are_rejected_without_email(): void
    {
        Mail::fake();
        $booking = $this->validBooking();
        $booking['mobile'] = '12345';
        $booking['preferred_date'] = now()->subDay()->toDateString();

        $response = $this->postJson('/book', $booking);

        $response->assertUnprocessable()->assertJsonValidationErrors(['mobile', 'preferred_date']);
        Mail::assertNothingSent();
    }

    public function test_honeypot_rejects_automated_submission(): void
    {
        Mail::fake();
        $booking = $this->validBooking();
        $booking['website'] = 'spam.example';

        $this->postJson('/book', $booking)->assertUnprocessable()->assertJsonValidationErrors('website');

        Mail::assertNothingSent();
    }

    public function test_mailable_escapes_free_text(): void
    {
        $booking = $this->validBooking();
        $booking['name'] = '<script>alert(1)</script>';
        $booking['notes'] = '<img src=x onerror=alert(1)>';

        $rendered = (new BookingEnquiry($booking))->render();

        $this->assertStringNotContainsString('<script>', $rendered);
        $this->assertStringNotContainsString('<img src=x', $rendered);
    }

    private function validBooking(): array
    {
        return [
            'name' => 'Sample Patient',
            'mobile' => '0917 123 4567',
            'email' => 'patient@example.com',
            'service' => 'cleaning',
            'dentist' => 'dr-maya-reyes',
            'preferred_date' => now()->addDays(2)->toDateString(),
            'time_slot' => '10:00 AM',
            'notes' => 'Morning preferred.',
            'consent' => '1',
            'website' => '',
        ];
    }
}
