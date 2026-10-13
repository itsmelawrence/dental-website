<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->safe()->except(['consent', 'website']);
        $recipient = config('clinic.booking.recipient_email') ?: config('clinic.contact.email');

        Mail::to($recipient)->send(new BookingEnquiry($data));

        $message = 'Thank you. Your appointment request has been sent. The clinic will confirm availability with you.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->route('contact')->with('booking_success', $message);
    }
}
