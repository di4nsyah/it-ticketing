@props(['status'])

@php
    $statusTone = [
        'open' => 'bg-sunken text-ink-soft',
        'in_progress' => 'bg-accent text-ink',
        'done' => 'border border-line bg-surface text-ink-soft',
        'closed' => 'border border-line bg-surface text-ink-faint',
        'cancelled' => 'bg-sunken text-ink-faint',
    ][$status->status] ?? 'bg-sunken text-ink-soft';

    $priorityTone = [
        'low' => 'text-ink-faint',
        'medium' => 'bg-sunken text-ink-soft',
        'high' => 'bg-ink text-canvas',
    ][$status->priority] ?? 'text-ink-faint';

    $isLive = $status->status === 'in_progress';
@endphp

<span {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-1.5']) }}>
    {{-- Priority: the single dark chip is what "urgent" looks like here. --}}
    <span class="chip {{ $priorityTone }}">
        @if ($status->priority === 'high')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3" aria-hidden="true">
                <path d="M12 6v6M12 18h.01" />
                <path d="M10.3 3.9 2.6 17.5A2 2 0 0 0 4.3 20.5h15.4a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" />
            </svg>
        @else
            <span class="h-1.5 w-1.5 rounded-full bg-current" aria-hidden="true"></span>
        @endif

        {{ $status->priorityLabel() }}
    </span>

    {{-- Status: accent is reserved for work currently in motion. --}}
    <span class="chip {{ $statusTone }}">
        @if ($isLive)
            <span class="pulse-dot relative h-1.5 w-1.5 rounded-full bg-accent-deep" aria-hidden="true"></span>
        @elseif ($status->status === 'done')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3" aria-hidden="true">
                <path d="M5 13l4 4L19 7" />
            </svg>
        @else
            <span class="h-1.5 w-1.5 rounded-full bg-current" aria-hidden="true"></span>
        @endif

        {{ $status->statusLabel() }}
    </span>
</span>
