@props(['user', 'size' => '12', 'rounded' => '2xl'])

@if($user->avatar)
<img src="{{ Storage::url($user->avatar) }}"
     alt="{{ $user->name }}"
     class="w-{{ $size }} h-{{ $size }} rounded-{{ $rounded }} object-cover shrink-0"/>
@else
<div class="w-{{ $size }} h-{{ $size }} rounded-{{ $rounded }} flex items-center justify-center font-bold text-black shrink-0"
     style="background:#FCB315; font-size: {{ ($size/4) }}rem;">
    {{ strtoupper(substr($user->name, 0, 1)) }}
</div>
@endif
