{{--
    MVC view: TicketController@index ngasih $tickets (10 per halaman) + $categories
    view cuma nampilin, nggak pernah query database sendiri
--}}
@php
    $isTeknisi = auth()->user()->isTeknisi();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div>
                <p class="stat-label">{{ $isTeknisi ? 'Antrean' : 'Riwayat' }}</p>
                {{-- judul ikut berubah, karena datanya juga beda --}}
                <h1 class="mt-3 font-display text-4xl font-light leading-[1.05] tracking-[-0.035em] text-ink sm:text-5xl">
                    {{ $isTeknisi ? 'Semua Ticket' : 'Ticket Saya' }}
                </h1>
            </div>

            {{-- teknisi nggak punya tombol ini karena dia nggak boleh create --}}
            @unless ($isTeknisi)
                <a href="{{ route('tickets.create') }}"
                   class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-ink px-5 py-2.5 text-sm font-medium text-canvas transition duration-200 hover:bg-ink/90">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" aria-hidden="true" class="h-3.5 w-3.5"><path d="M12 5v14M5 12h14" /></svg>
                    {{ __('Buat Ticket') }}
                </a>
            @endunless
        </div>
    </x-slot>

    <div class="px-5 py-12 sm:px-8">
        <div class="mx-auto max-w-5xl space-y-4" x-data="{ loading: false }">

            {{-- pesan flash dari controller, cuma muncul sekali terus dihapus --}}
            @if (session('success'))
                <div class="flex items-center gap-3 rounded-2xl border border-line bg-surface px-5 py-4">
                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-accent">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                             stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 text-ink" aria-hidden="true">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <p class="text-sm text-ink">{{ session('success') }}</p>
                </div>
            @endif

            <div class="card overflow-hidden">

                {{-- data asli, muncul pas halaman selesai dimuat --}}
                <div x-show="! loading" class="divide-y divide-line">
                    {{-- @forelse = @foreach + tampilan pas datanya kosong --}}
                    @forelse ($tickets as $ticket)
                        {{--
                            route() bikin URL dari nama route, bukan nulis /tickets/5 manual
                            klik -> route tickets.show -> TicketController@show
                        --}}
                        <a href="{{ route('tickets.show', $ticket) }}" @click="loading = true"
                           class="group flex items-center gap-4 px-5 py-4 transition duration-200 hover:bg-sunken sm:px-6">
                            {{-- bulat di kiri tiap baris, kuning kalo lagi dikerjakan --}}
                            <span @class([
                                'relative h-2.5 w-2.5 shrink-0 rounded-full',
                                'bg-accent' => $ticket->status === 'progress',
                                'bg-ink' => $ticket->status !== 'progress',
                            ]) aria-hidden="true"></span>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-ink">
                                    <span class="mr-2 font-display text-xs text-ink-faint">#{{ $ticket->id }}</span>{{ $ticket->title }}
                                </p>
                                <p class="mt-1 truncate text-xs text-ink-faint">
                                    {{ $isTeknisi ? $ticket->user->name : $ticket->category->name }}
                                    &middot; {{ $ticket->created_at->format('d M Y') }}
                                </p>
                            </div>

                            <x-ticket-badges :status="$ticket" class="shrink-0" />

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"
                                 class="hidden h-4 w-4 shrink-0 text-ink-faint transition duration-200 group-hover:translate-x-0.5 sm:block">
                                <path d="M9 6l6 6-6 6" />
                            </svg>
                        </a>
                    @empty
                        {{-- @empty = tampilan pas $tickets-nya kosong --}}
                        <x-state-empty icon="inbox"
                                       title="Belum ada ticket"
                                       hint="{{ $isTeknisi ? 'Belum ada tiket yang dibuat karyawan.' : 'Ticket yang kamu buat akan muncul di sini.' }}">
                            @unless ($isTeknisi)
                                <a href="{{ route('tickets.create') }}"
                                   class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-ink px-5 py-2.5 text-sm font-medium text-canvas transition duration-200 hover:bg-ink/90">
                                    {{ __('Buat Ticket') }}
                                </a>
                            @endunless
                        </x-state-empty>
                    @endforelse
                </div>

{{-- skeleton: bentuknya sama sama data beneran, biar ga ada pergeseran --}}
                <div x-show="loading" x-cloak class="divide-y divide-line">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="flex items-center gap-4 px-5 py-4 sm:px-6">
                            <x-skeleton variant="circle" class="h-2.5 w-2.5 shrink-0" />
                            <div class="flex-1 space-y-2">
                                <x-skeleton class="h-3.5" style="width: {{ 55 - ($i % 3) * 8 }}%" />
                                <x-skeleton class="h-2.5 w-1/3" />
                            </div>
                            <x-skeleton class="h-5 w-28 shrink-0" />
                        </div>
                    @endfor
                </div>
            </div>

            {{-- tombol halaman, datanya udah dibagi per 10 dari paginate(10) di controller --}}
            @if ($tickets->hasPages())
                <div class="pt-2">{{ $tickets->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
