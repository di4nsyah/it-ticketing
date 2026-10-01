<x-blank title="404 — {{ config('app.name', 'Laravel') }}">
    <p class="stat-label">404</p>

    <h1 class="mt-4 font-display text-5xl font-light leading-[1.05] tracking-[-0.035em] text-ink">
        Halaman ini tidak ditemukan.
    </h1>

    <p class="mt-4 text-sm leading-relaxed text-ink-soft">
        Alamatnya mungkin salah, atau halaman yang kamu cari sudah tidak ada.
    </p>

    <a href="{{ url('/') }}"
       class="mt-8 inline-flex cursor-pointer items-center gap-2 rounded-full bg-ink px-5 py-2.5 text-sm font-medium text-canvas transition duration-200 hover:bg-ink/90">
        Kembali
    </a>
</x-layouts.blank>
