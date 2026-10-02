{{--
    MVC view: TicketController@create ngasih $categories buat isi dropdown kategori
--}}
<x-app-layout>
    <x-slot name="header">
        <p class="stat-label">Ticket Baru</p>
        <h1 class="mt-3 font-display text-4xl font-light leading-[1.05] tracking-[-0.035em] text-ink sm:text-5xl">
            Buat Ticket
        </h1>
    </x-slot>

    <div class="px-5 py-12 sm:px-8">
        <div class="mx-auto max-w-2xl">
            <div class="card rise overflow-hidden">
                <div class="px-7 pt-8 sm:px-9">
                    <p class="max-w-md text-[13px] leading-relaxed text-ink-soft">
                        Jelaskan masalahnya sedetail mungkin supaya teknisi bisa menindaklanjuti tanpa perlu bertanya ulang.
                    </p>
                </div>

                {{--
                    alur: form -> POST /tickets -> route tickets.store ->
                    TicketController@store -> validasi -> create() -> database
                --}}
                <form method="POST" action="{{ route('tickets.store') }}" class="px-7 pb-8 pt-7 sm:px-9">
                    {{-- token biar request ini pasti dari form kita, cegah serangan CSRF --}}
                    @csrf

                    <div>
                        <x-input-label for="title" value="Judul" />
                        {{-- old() buat nampilin lagi input kalo validasi gagal --}}
                        <x-text-input id="title" name="title" type="text" class="mt-2" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-7">
                        <x-input-label for="description" value="Deskripsi" />
                        <textarea id="description" name="description" rows="6" required
                            class="field mt-2 resize-y">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mt-7 grid gap-7 sm:grid-cols-2">
                        <div>
                            <x-input-label for="category_id" value="Kategori" />
                            {{-- $categories dari controller, makanya view ga perlu query sendiri --}}
                            <select id="category_id" name="category_id" required class="field mt-2">
                                <option value="">-- Pilih kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="priority" value="Prioritas" />
                            <select id="priority" name="priority" required class="field mt-2">
                                <option value="low" @selected(old('priority') === 'low')>Rendah</option>
                                <option value="medium" @selected(old('priority', 'medium') === 'medium')>Sedang</option>
                                <option value="high" @selected(old('priority') === 'high')>Tinggi</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-9 flex flex-wrap items-center gap-4 border-t border-line pt-7">
                        <x-primary-button>Kirim Ticket</x-primary-button>
                        <a href="{{ route('tickets.index') }}" class="text-[13px] text-ink-soft transition hover:text-ink">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
