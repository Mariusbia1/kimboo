@props(['size' => 26])

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"
     {{ $attributes->merge([
         'style' => "width:{$size}px; height:{$size}px;",
     ]) }}
     aria-label="Certifié"
     role="img">
    <path fill="#1877F2" d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Z"/>
    <path fill="#FFFFFF" d="m438-338 226-226-56-58-170 170-86-84-56 56 142 142Z"/>
</svg>
