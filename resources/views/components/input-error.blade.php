@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'space-y-1.5 text-sm text-ink']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start gap-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-danger" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 8v4.5M12 16h.01" />
                </svg>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
