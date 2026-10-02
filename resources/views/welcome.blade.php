<x-blank title="{{ config('app.name', 'Laravel') }}">
    <p class="stat-label">IT Ticketing</p>

    <h1 class="mt-4 font-display text-4xl font-light leading-[1.08] tracking-[-0.035em] text-ink sm:text-5xl">
        Laporan masalah IT,<br>
        satu tempat.
    </h1>

    <p class="mx-auto mt-5 max-w-sm text-sm leading-relaxed text-ink-soft">
        Buat ticket, pantau statusnya, dan biarkan teknisi menindaklanjuti tanpa perlu bolak-balik.
    </p>

    <div class="mt-9 flex items-center justify-center gap-3">
        <a href="{{ route('login') }}"
           class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-ink px-5 py-2.5 text-sm font-medium text-canvas transition duration-200 hover:bg-ink/90">
            Masuk
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
        </a>

        @auth
            <a href="{{ route('dashboard') }}"
               class="inline-flex cursor-pointer items-center rounded-full border border-line px-5 py-2.5 text-sm font-medium text-ink transition duration-200 hover:border-ink">
                Dashboard
            </a>
        @endauth
    </div>

    <div class="mt-14 grid gap-3 text-left sm:grid-cols-3">
        @foreach ([
            ['Buat', 'Kirim detail masalah.'],
            ['Pantau', 'Lihat statusnya kapan saja.'],
            ['Tuntas', 'Technisi menindaklanjuti.'],
        ] as $i => [$step, $note])
            <div class="rise rounded-2xl border border-line bg-surface p-4" style="animation-delay: {{ $i * 80 }}ms">
                <span class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full {{ $i === 1 ? 'bg-accent' : 'bg-ink' }}" aria-hidden="true"></span>
                    <span class="stat-label">{{ $step }}</span>
                </span>
                <p class="mt-3 text-[13px] leading-relaxed text-ink-soft">{{ $note }}</p>
            </div>
        @endforeach
    </div>
</x-blank>
