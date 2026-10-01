@php
    $isTeknisi = auth()->user()->isTeknisi();
    $backLabel = $isTeknisi ? 'Semua Ticket' : 'Ticket Saya';
@endphp

<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-1.5 text-[13px] text-ink-faint transition hover:text-ink">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5" aria-hidden="true">M15 6l-6 6 6 6</svg>
            {{ $backLabel }}
        </a>

        <div class="mt-6 flex flex-wrap items-end justify-between gap-6">
            <div class="min-w-0">
                <p class="stat-label">Ticket #{{ $ticket->id }}</p>
                <h1 class="mt-3 font-display text-4xl font-light leading-[1.05] tracking-[-0.035em] text-ink sm:text-5xl">
                    {{ $ticket->title }}
                </h1>
            </div>

            <x-ticket-badges :status="$ticket" />
        </div>
    </x-slot>

    <div class="px-5 py-12 sm:px-8">
        <div class="mx-auto max-w-2xl space-y-4">

            <div class="card card-pad rise">
                <p class="whitespace-pre-line text-sm leading-relaxed text-ink-soft">{{ $ticket->description }}</p>
            </div>

            {{-- Deliberately uneven: the description is the subject, metadata recedes. --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="card px-6 py-5">
                    <p class="stat-label">Pembuat</p>
                    <p class="mt-3 text-sm text-ink">{{ $ticket->user->name }}</p>
                </div>

                <div class="card px-6 py-5">
                    <p class="stat-label">Kategori</p>
                    <p class="mt-3 text-sm text-ink">{{ $ticket->category->name }}</p>
                </div>

                <div class="card px-6 py-5">
                    <p class="stat-label">Dibuat</p>
                    <p class="mt-3 font-display text-lg font-light tracking-[-0.02em] text-ink">
                        {{ $ticket->created_at->format('d M Y') }}
                    </p>
                </div>

                <div class="card px-6 py-5">
                    <p class="stat-label">Diperbarui</p>
                    <p class="mt-3 font-display text-lg font-light tracking-[-0.02em] text-ink">
                        {{ $ticket->updated_at->format('d M Y') }}
                    </p>
                </div>
            </div>

            @if (auth()->user()->can('cancel', $ticket))
                <div class="card card-pad flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <p class="text-sm text-ink">Batalkan ticket ini?</p>
                        <p class="mt-1 text-[13px] text-ink-faint">Hanya bisa selama status masih terbuka.</p>
                    </div>

                    <form method="POST" action="{{ route('tickets.cancel', $ticket) }}"
                          onsubmit="return confirm('Batalkan ticket ini?')">
                        @csrf
                        @method('PATCH')
                        <x-danger-button>Batalkan Ticket</x-danger-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
