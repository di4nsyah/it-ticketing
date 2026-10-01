@props(['name' => config('app.name', 'Laravel')])

{{-- Wordmark only. No mark, no icon. --}}
<span {{ $attributes->merge(['class' => 'font-display text-[17px] font-semibold tracking-[-0.03em] text-ink']) }}>
    {{ $name }}
</span>
