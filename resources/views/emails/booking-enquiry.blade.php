<h1>New appointment enquiry</h1>
<p>This is a request, not a confirmed appointment. Please contact the patient to confirm.</p>
<dl>
    <dt>Name</dt><dd>{{ $booking['name'] }}</dd>
    <dt>Mobile</dt><dd>{{ $booking['mobile'] }}</dd>
    <dt>Email</dt><dd>{{ $booking['email'] ?? 'Not provided' }}</dd>
    <dt>Service</dt><dd>{{ $booking['service'] }}</dd>
    <dt>Dentist</dt><dd>{{ $booking['dentist'] ?? 'No preference' }}</dd>
    <dt>Preferred date</dt><dd>{{ $booking['preferred_date'] }}</dd>
    <dt>Time slot</dt><dd>{{ $booking['time_slot'] }}</dd>
    <dt>Notes</dt><dd>{{ $booking['notes'] ?? 'None' }}</dd>
</dl>
