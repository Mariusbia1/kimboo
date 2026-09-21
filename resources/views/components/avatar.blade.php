@props([
    'user' => null,
    'name' => null,
    'src' => null,
    'size' => '12',
    'rounded' => '2xl',
    'full' => false
])

@php
    $userName = $name ?? (is_object($user) ? ($user->name ?? 'K') : (string)($user ?? 'K'));
    $parts = preg_split('/\s+/', trim($userName));
    if (count($parts) >= 2) {
        $initials = strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
    } else {
        $initials = strtoupper(mb_substr($userName, 0, 1));
    }

    $roundedClass = match($rounded) {
        'full' => 'rounded-full',
        '3xl'  => 'rounded-3xl',
        '2xl'  => 'rounded-2xl',
        'xl'   => 'rounded-xl',
        'lg'   => 'rounded-lg',
        'none' => 'rounded-none',
        default => 'rounded-' . $rounded,
    };

    $avatarUrl = null;
    if (!empty($src)) {
        $avatarUrl = $src;
    } elseif (is_object($user) && !empty($user->avatar)) {
        $avatarUrl = str_starts_with($user->avatar, 'http') ? $user->avatar : Storage::url($user->avatar);
    }

    $isFull = $full || $size === 'full';
    $sizeNum = is_numeric($size) ? (int)$size : 12;
    $dimRem = round($sizeNum * 0.25, 2);
    $fontSizeRem = $isFull ? 3 : max(0.65, round($sizeNum * 0.085, 2));
@endphp

@if($avatarUrl)
<img src="{{ $avatarUrl }}"
     alt="{{ $userName }}"
     loading="lazy"
     decoding="async"
     class="{{ $isFull ? 'w-full h-full' : '' }} {{ $roundedClass }} object-cover object-top shrink-0 transition duration-300 group-hover:scale-105"
     style="object-position: center top; @if(!$isFull) width:{{ $dimRem }}rem; height:{{ $dimRem }}rem; @endif"
     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"/>
<div class="{{ $isFull ? 'w-full h-full' : '' }} {{ $roundedClass }} hidden relative overflow-hidden shrink-0 flex items-center justify-center font-black text-black tracking-wider select-none"
     @if(!$isFull) style="width:{{ $dimRem }}rem; height:{{ $dimRem }}rem; font-size:{{ $fontSizeRem }}rem; background: #FCB315;"
     @else style="font-size:{{ $fontSizeRem }}rem; background: #FCB315;" @endif>
    <span class="relative z-10 leading-none">{{ $initials }}</span>
</div>
@else
<div class="{{ $isFull ? 'w-full h-full' : '' }} {{ $roundedClass }} relative overflow-hidden shrink-0 flex items-center justify-center font-black text-black tracking-wider select-none"
     @if(!$isFull) style="width:{{ $dimRem }}rem; height:{{ $dimRem }}rem; font-size:{{ $fontSizeRem }}rem; background: #FCB315;"
     @else style="font-size:{{ $fontSizeRem }}rem; background: #FCB315;" @endif>
    <span class="relative z-10 leading-none">{{ $initials }}</span>
</div>
@endif
