@props(['name'])
<svg {{ $attributes->merge(['viewBox' => '0 0 32 32', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.7', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('sparkle')<path d="M16 3l2.7 10.3L29 16l-10.3 2.7L16 29l-2.7-10.3L3 16l10.3-2.7L16 3z"/>@break
        @case('sun')<circle cx="16" cy="16" r="5"/><path d="M16 2v5M16 25v5M2 16h5M25 16h5M6.1 6.1l3.5 3.5M22.4 22.4l3.5 3.5M25.9 6.1l-3.5 3.5M9.6 22.4l-3.5 3.5"/>@break
        @case('grid')<path d="M4 11c4-2 8-2 12 0s8 2 12 0M4 21c4-2 8-2 12 0s8 2 12 0M8 6l2 20M24 6l-2 20"/>@break
        @case('tooth')<path d="M16 7c-4-3-10-3-11 3-1 6 3 16 6 16 2 0 1-7 5-7s3 7 5 7c3 0 7-10 6-16-1-6-7-6-11-3z"/>@break
        @case('diamond')<path d="M9 5h14l6 8-13 15L3 13l6-8zM3 13h26M9 5l-2 8 9 15 9-15-2-8"/>@break
        @case('plus')<path d="M16 5v22M5 16h22"/><circle cx="16" cy="16" r="13"/>@break
        @case('heart')<path d="M16 27L5.8 17.4C-.3 11.7 7.4 2.9 14 8.8l2 1.8 2-1.8c6.6-5.9 14.3 2.9 8.2 8.6L16 27z"/>@break
        @default<path d="M16 3l2.7 10.3L29 16l-10.3 2.7L16 29l-2.7-10.3L3 16l10.3-2.7L16 3z"/>
    @endswitch
</svg>
