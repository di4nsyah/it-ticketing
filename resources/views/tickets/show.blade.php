{{--
    MVC view: TicketController@show ngasih $ticket
    $ticket ini hasil route model binding, bukan diisi manual di view
--}}
@php
    $isTeknisi = auth()->user()->isTeknisi();
    $backLabel = $isTeknisi ? 'Semua Ticket' : 'Ticket Saya';
@endphp

<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-1.5 text-[13px] text-ink-faint transition hover:text-ink">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5" aria-hidden="true"><path d="M15 6l-6 6 6 6" /></svg>
            {{ $backLabel }}
        </a>

        <div class="mt-6 flex flex-wrap items-end justify-between gap-6">
            <div class="min-w-0">
                <p class="stat-label">Ticket #{{ $ticket->id }}</p>
                {{-- $ticket->title = data langsung dari kolom title di tabel tickets --}}
                <h1 class="mt-3 font-display text-4xl font-light leading-[1.05] tracking-[-0.035em] text-ink sm:text-5xl">
                    {{ $ticket->title }}
                </h1>
            </div>

            {{-- labelnya ada di Model Ticket, view cuma manggil --}}
            <x-ticket-badges :status="$ticket" />
        </div>
    </x-slot>

    <div class="px-5 py-12 sm:px-8">
        <div class="mx-auto max-w-2xl space-y-4">

            <div class="card card-pad rise">
                <p class="whitespace-pre-line text-sm leading-relaxed text-ink-soft">{{ $ticket->description }}</p>
            </div>

            {{--
                $ticket->user->name sama $ticket->category->name itu relationship.
                kolom user_id cuma nyimpen angka, relationship belongsTo()
                di Model Ticket yg ambil datanya dari tabel lain
            --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="card px-6 py-5">
                    <p class="stat-label">Pembuat</p>
                    <p class="mt-3 text-sm text-ink">{{ $ticket->user->name }}</p>
                </div>

                <div class="card px-6 py-5">
                    <p class="stat-label">Kategori</p>
                    <p class="mt-3 text-sm text-ink">{{ $ticket->category->name }}</p>
                </div>

                {{-- created_at & updated_at dibuat otomatis sama $table->timestamps() di migration --}}
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

            {{-- 
                form teknisi: ubah status dan prioritas 
                alur: submit -> PATCH /tickets/{id} -> route tickets.update 
                -> TicketController@update -> Gate::authorize('update') -> validate -> update() -> database
            --}}

            @if (auth()->user()->can('update', $ticket))
            <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="card card-pad space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="status" class="stat-label">Status</label>
                        <select id="status" name="status" class="field mt-2">
                            <option value="{{ $ticket->status }}" selected>{{ $ticket->statusLabel() }} (saat ini)</option>
                            {{-- nextStatuses() ada di Model: cuma nampilin status yang boleh dipilih berikutnya --}}
                            @foreach ($ticket->nextStatuses() as $next)
                                <option value="{{ $next }}">{{ \App\Models\Ticket::STATUSES[$next] }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <label for="priority" class="stat-label">Prioritas</label>
                        <select id="priority" name="priority" class="field mt-2">
                            @foreach (\App\Models\Ticket::PRIORITIES as $value => $label)
                                <option value="{{ $value }}" @selected($ticket->priority === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                    </div>
                </div>

                <x-primary-button>Simpan Perubahan</x-primary-button>
            </form>
            @endif

            {{--
                authorization di sisi view: @can nyembunyiin tombol kalo nggak boleh
                controller pake Gate::authorize yg malah lempar 403
                aturannya tetap sama, di TicketPolicy
            --}}
            @if (auth()->user()->can('cancel', $ticket))
                <div class="card card-pad flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <p class="text-sm text-ink">Batalkan ticket ini?</p>
                        <p class="mt-1 text-[13px] text-ink-faint">Hanya bisa selama status masih terbuka.</p>
                    </div>

{{--
                        alur: klik -> PATCH /tickets/{id}/cancel -> route tickets.cancel
                        -> TicketController@cancel -> update() -> database
                    --}}
                    <form method="POST" action="{{ route('tickets.cancel', $ticket) }}"
                          onsubmit="return confirm('Batalkan ticket ini?')">
                        @csrf

                        {{--
                            method spoofing: form html cuma bisa GET/POST.
                            jadi tetep POST tapi disisipin field _method=PATCH,
                            Laravel baca itu dan anggap requestnya PATCH
                        --}}
                        @method('PATCH')
                        <x-danger-button>Batalkan Ticket</x-danger-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
