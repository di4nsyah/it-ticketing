@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'flex items-center gap-3 rounded-2xl bg-ink px-4 py-2.5 text-sm font-medium text-canvas'
        : 'flex items-center gap-3 rounded-2xl px-4 py-2.5 text-sm font-medium text-ink-soft transition duration-200 hover:bg-surface hover:text-ink';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'aria-current' => ($active ?? false) ? 'page' : null]) }}>
    {{ $slot }}
</a>
