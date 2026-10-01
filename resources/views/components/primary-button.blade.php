<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex cursor-pointer items-center justify-center gap-2 rounded-full bg-ink px-5 py-2.5 text-sm font-medium text-canvas transition duration-200 hover:bg-ink/90 active:scale-[0.98] disabled:pointer-events-none disabled:opacity-40']) }}>
    {{ $slot }}
</button>
