<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex cursor-pointer items-center justify-center gap-2 rounded-full border border-line bg-surface px-5 py-2.5 text-sm font-medium text-ink transition duration-200 ease-out hover:border-line-strong hover:bg-sunken active:scale-[0.98] disabled:pointer-events-none disabled:opacity-40']) }}>
    {{ $slot }}
</button>
