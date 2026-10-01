<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div>
                <p class="stat-label">{{ $isTeknisi ? 'Panel Teknisi' : 'Portal Karyawan' }}</p>
                <h1 class="mt-3 font-display text-4xl font-light leading-[1.05] tracking-[-0.035em] text-ink sm:text-5xl">
                    Halo, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}
                </h1>
            </div>

            <span class="chip {{ $isTeknisi ? 'bg-ink text-canvas' : 'border border-line text-ink-soft' }}">
                {{ $isTeknisi ? 'Teknisi' : 'Karyawan' }}
            </span>
        </div>
    </x-slot>

    <div class="px-5 py-12 sm:px-8">
        <div class="mx-auto max-w-5xl space-y-4">

            {{-- Bento: one oversized figure, three quiet ones. --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-[1.6fr_1fr]">
                <div class="card rise card-pad flex flex-col justify-between">
                    <div>
                        <p class="stat-label">{{ $isTeknisi ? 'Semua Ticket' : 'Ticket Saya' }}</p>
                        <p class="stat-hero mt-6">{{ $total }}</p>
                    </div>

                    <div class="mt-8 flex items-center gap-3">
                        <span class="h-1 w-10 rounded-full bg-accent" aria-hidden="true"></span>
                        <p class="text-[13px] text-ink-soft">
                            @if ($total === 0)
                                Belum ada ticket tercatat.
                            @else
                                {{ $counts->get('open', 0) }} menunggu, {{ $counts->get('in_progress', 0) }} sedang dikerjakan.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="grid gap-4">
                    @php
                        $secondary = [
                            ['label' => 'Terbuka', 'key' => 'open'],
                            ['label' => 'Diproses', 'key' => 'in_progress'],
                            ['label' => 'Selesai', 'key' => 'done'],
                        ];
                    @endphp

                    @foreach ($secondary as $i => $item)
                        <div class="card rise flex items-center justify-between px-6 py-5"
                             style="animation-delay: {{ 60 + ($i * 70) }}ms">
                            <p class="text-sm text-ink-soft">{{ $item['label'] }}</p>
                            <p class="stat-value">{{ $counts->get($item['key'], 0) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Latest activity --}}
            <div class="card mt-4 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5">
                    <p class="stat-label">Aktivitas Terakhir</p>
                    <a href="{{ route('tickets.index') }}" class="text-[13px] text-ink-soft transition hover:text-ink">
                        Lihat semua
                    </a>
                </div>

                @forelse ($recent as $ticket)
                    <a href="{{ route('tickets.show', $ticket) }}"
                       class="flex items-center gap-4 border-t border-line px-6 py-4 transition duration-200 hover:bg-sunken">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-ink">{{ $ticket->title }}</p>
                            <p class="mt-0.5 truncate text-xs text-ink-faint">
                                {{ $isTeknisi ? $ticket->user->name : $ticket->category->name }}
                                &middot; {{ $ticket->created_at->format('d M Y') }}
                            </p>
                        </div>

                        <x-ticket-badges :status="$ticket" />
                    </a>
                @empty
                    <div class="border-t border-line">
                        <x-state-empty icon="inbox"
                                       title="Belum ada ticket"
                                       hint="{{ $isTeknisi ? 'Ticket yang dibuat karyawan akan muncul di sini.' : 'Ticket yang kamu buat akan muncul di sini.' }}" />
                    </div>
                @endforelse
            </div>

            {{-- Next step --}}
            <div class="card card-pad flex flex-wrap items-center justify-between gap-6">
                <p class="max-w-sm text-[13px] leading-relaxed text-ink-soft">
                    {{ $isTeknisi
                        ? 'Tiket yang dibuat karyawan masuk ke antrean di bawah dan bisa kamu ubah statusnya.'
                        : 'Buat ticket, isi detail masalahnya, lalu teknisi akan menindaklanjuti.' }}
                </p>

                @if (! $isTeknisi)
                    <a href="{{ route('tickets.create') }}"
                       class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-ink px-5 py-2.5 text-sm font-medium text-canvas transition duration-200 hover:bg-ink/90">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" aria-hidden="true" class="h-3.5 w-3.5">M12 5v14M5 12h14</svg>
                        {{ __('Buat Ticket') }}
                    </a>
                @else
                    <a href="{{ route('tickets.index') }}"
                       class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-line px-5 py-2.5 text-sm font-medium text-ink transition duration-200 hover:border-ink">
                        {{ __('Buka Antrean') }}
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
