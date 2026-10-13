<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'regex:/^(?:09\d{9}|\+639\d{9})$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'service' => ['required', Rule::in(array_column(config('clinic.services'), 'slug'))],
            'dentist' => ['nullable', Rule::in(array_column(config('clinic.dentists'), 'slug'))],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', Rule::in(config('clinic.booking.time_slots'))],
            'notes' => ['nullable', 'string', 'max:500'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['mobile' => preg_replace('/[\s\-()]/', '', (string) $this->input('mobile'))]);
    }
}
