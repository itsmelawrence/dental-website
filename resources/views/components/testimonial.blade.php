@props(['testimonial'])
<blockquote class="testimonial-card"><div aria-label="{{ $testimonial['rating'] }} out of 5 stars">{{ str_repeat('★', $testimonial['rating']) }}</div><p>“{{ $testimonial['text'] }}”</p><footer>{{ $testimonial['name'] }} · {{ $testimonial['service'] }}</footer></blockquote>
