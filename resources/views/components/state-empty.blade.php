@props([
    'icon' => 'inbox',
    'title',
    'hint' => null,
])

@php
    $paths = [
        'inbox' => 'M3 13h4l2 3h6l2-3h4M3 13l2.5-7.5A2 2 0 0 1 7.4 4h9.2a2 2 0 0 1 1.9 1.5L21 13v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4Z',
        'search' => 'M11 18a7 7 0 100-14 7 7 0 000 14Zm5-2 5 5',
        'alert' => 'M12 8v5m0 3h.01M10.3 4.3 2.6 18a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 4.3a2 2 0 0 0-3.4 0Z',
    ];

    $path = $paths[$icon] ?? $paths['inbox'];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center px-6 py-20 text-center']) }}>
    <span class="grid h-14 w-14 place-items-center rounded-full border border-line bg-sunken text-ink-faint">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
             stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6" aria-hidden="true">
            <path d="{{ $path }}" />
        </svg>
    </span>

    <p class="mt-6 font-display text-lg font-medium tracking-tight text-ink">{{ $title }}</p>

    @if ($hint)
        <p class="mt-2 max-w-sm text-sm leading-relaxed text-ink-faint">{{ $hint }}</p>
    @endif

    @if (! $slot->isEmpty())
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
