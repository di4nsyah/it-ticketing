@props(['variant' => 'line', 'class' => ''])

@php
    $shape = match ($variant) {
        'block' => 'rounded-2xl',
        'title' => 'h-5 w-2/3 rounded-md',
        'circle' => 'rounded-full',
        default => 'rounded-full',
    };
@endphp

<span {{ $attributes->merge(['class' => 'skeleton block '.$shape.' '.$class, 'aria-hidden' => 'true']) }}></span>
