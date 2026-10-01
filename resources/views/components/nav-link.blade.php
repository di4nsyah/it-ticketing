@props(['active'])

@php
    $base = 'inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-[13px] font-medium transition duration-200';
    $classes = ($active ?? false)
        ? $base.' bg-ink text-canvas'
        : $base.' text-ink-soft hover:bg-surface hover:text-ink';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'aria-current' => ($active ?? false) ? 'page' : null]) }}>
    {{ $slot }}
</a>
