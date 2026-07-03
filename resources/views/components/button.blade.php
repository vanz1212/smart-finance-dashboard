@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'icon' => null
])

@php
    $classes = 'nexio-btn nexio-btn-' . $variant;
@endphp

@if($href)
    <a href="{{ $href }}" wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) {!! $icon !!} @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) {!! $icon !!} @endif
        {{ $slot }}
    </button>
@endif
