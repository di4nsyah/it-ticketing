{{--
    MVC blade component: dipanggil pake <x-ticket-badges :status="$ticket" />
    dipakai di 3 halaman, makanya dibikin component biar ga ditulis ulang
--}}
@props(['status'])

@php
    // pemetaan value database ke warna. labelnya sendiri ada di Model Ticket
    // (statusLabel/priorityLabel), file ini cuma milih warnanya
    // ?? buat jaga-jaga kalo ada status baru yg belum ada di daftar ini
    $statusTone = [
        'open' => 'bg-sunken text-ink-soft',
        'progress' => 'bg-accent text-ink',
        'done' => 'border border-line bg-surface text-ink-soft',
        'cancelled' => 'bg-sunken text-ink-faint',
    ][$status->status] ?? 'bg-sunken text-ink-soft';

    $priorityTone = [
        'low' => 'text-ink-faint',
        'medium' => 'bg-sunken text-ink-soft',
        'high' => 'bg-ink text-canvas',
    ][$status->priority] ?? 'text-ink-faint';

    // status ini doang yg nampilin titik beranimasi, biar keliatan yg lagi dikerjakan
    $isLive = $status->status === 'progress';
@endphp

<span {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-1.5']) }}>
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
