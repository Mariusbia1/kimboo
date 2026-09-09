@props(['size' => 'default', 'dark' => false, 'white' => false])

@php
$heightClass = match($size) {
    'sm' => 'h-7',
    'lg' => 'h-11 sm:h-12',
    default => 'h-9 sm:h-10',
};
$logoSrc = $white ? asset('images/logo-white.webp') : asset('images/logo.webp');
$fallbackSrc = $white ? asset('images/logo-white.png') : asset('images/logo.png');
@endphp

<a href="{{ url('/') }}" class="inline-flex items-center select-none shrink-0 transition-opacity duration-200 hover:opacity-90">
    <picture>
        <source srcset="{{ $logoSrc }}" type="image/webp">
        <img src="{{ $fallbackSrc }}"
             alt="Kimboo"
             class="{{ $heightClass }} w-auto object-contain"
             loading="eager"
             fetchpriority="high">
    </picture>
</a>
