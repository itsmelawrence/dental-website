<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingEnquiry extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('clinic.name')),
            subject: 'New appointment enquiry — '.config('clinic.name'),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-enquiry');
    }

    public function attachments(): array
    {
        return [];
    }
}
