@php
    $user = Auth::user();
    $ticketLabel = $user->isTeknisi() ? 'Semua Ticket' : 'Ticket Saya';
    $initials = str($user->name)->substr(0, 2)->upper();
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-line bg-canvas/90 backdrop-blur-md">
    <div class="mx-auto max-w-5xl px-5 sm:px-8">
        <div class="flex h-16 items-center justify-between gap-6">
            <a href="{{ route('dashboard') }}" class="shrink-0 rounded-md">
                <x-application-logo />
            </a>

            {{-- Primary Navigation Menu --}}
            <div class="hidden items-center gap-1.5 sm:flex">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5" aria-hidden="true">
                        <path d="M3.5 10.5 12 4l8.5 6.5V19a1 1 0 0 1-1 1h-4v-6h-7v6h-4a1 1 0 0 1-1-1v-8.5Z" />
                    </svg>
                    {{ __('Dashboard') }}
                </x-nav-link>

                <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.index', 'tickets.show')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5" aria-hidden="true">
                        <path d="M3 9.5V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2.5a2.5 2.5 0 0 0 0 5V17a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2.5a2.5 2.5 0 0 0 0-5Z" />
                        <path d="M14.5 5.5v13" stroke-dasharray="1.5 3" />
                    </svg>
                    {{ $ticketLabel }}
                </x-nav-link>

                @unless ($user->isTeknisi())
                    <x-nav-link :href="route('tickets.create')" :active="request()->routeIs('tickets.create')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                             stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        {{ __('Buat Ticket') }}
                    </x-nav-link>
                @endunless
            </div>

            {{-- Settings Dropdown --}}
            <div class="hidden items-center sm:flex">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-line bg-surface py-1 pl-1 pr-2.5 text-sm font-medium text-ink transition duration-200 hover:border-line-strong">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-accent text-[11px] font-semibold text-ink">
                                {{ $initials }}
                            </span>
                            <span class="max-w-[8rem] truncate">{{ $user->name }}</span>
                            <svg class="h-3 w-3 text-ink-faint" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-3 py-2">
                            <p class="text-sm font-medium text-ink">{{ $user->name }}</p>
                            <p class="truncate text-xs text-ink-faint">{{ $user->email }}</p>
                        </div>

                        <div class="my-1 h-px bg-line"></div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        {{-- Authentication --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex cursor-pointer items-center justify-center rounded-xl p-2 text-ink-soft transition duration-200 hover:bg-surface hover:text-ink">
                    <span class="sr-only">Buka menu navigasi</span>
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-width="1.6" d="M4 8h16M4 16h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-width="1.6" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Responsive Navigation Menu --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-line bg-canvas sm:hidden">
        <div class="space-y-1 px-5 py-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.index', 'tickets.show')">
                {{ $ticketLabel }}
            </x-responsive-nav-link>

            @unless ($user->isTeknisi())
                <x-responsive-nav-link :href="route('tickets.create')" :active="request()->routeIs('tickets.create')">
                    {{ __('Buat Ticket') }}
                </x-responsive-nav-link>
            @endunless
        </div>

        {{-- Responsive Settings Options --}}
        <div class="border-t border-line px-5 py-4">
            <p class="text-sm font-medium text-ink">{{ $user->name }}</p>
            <p class="truncate text-xs text-ink-faint">{{ $user->email }}</p>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                {{-- Authentication --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
