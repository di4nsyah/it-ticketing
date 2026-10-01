<x-blank title="403 — {{ config('app.name', 'Laravel') }}">
    <p class="stat-label">403</p>

    <h1 class="mt-4 font-display text-5xl font-light leading-[1.05] tracking-[-0.035em] text-ink">
        Kamu tidak punya akses ke halaman ini.
    </h1>

    <p class="mt-4 text-sm leading-relaxed text-ink-soft">
        Halaman ini hanya untuk teknisi. Kalau kamu merasa ini keliru, hubungi administrator IT.
    </p>

    <a href="{{ url('/') }}"
       class="mt-8 inline-flex cursor-pointer items-center gap-2 rounded-full bg-ink px-5 py-2.5 text-sm font-medium text-canvas transition duration-200 hover:bg-ink/90">
        Kembali
    </a>
</x-layouts.blank>
