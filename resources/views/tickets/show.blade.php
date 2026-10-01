<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Ticket #{{ $ticket->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold">{{ $ticket->title }}</h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>

                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-500">Pembuat</dt><dd>{{ $ticket->user->name }}</dd></div>
                    <div><dt class="text-gray-500">Kategori</dt><dd>{{ $ticket->category->name }}</dd></div>
                    <div><dt class="text-gray-500">Prioritas</dt><dd>{{ $ticket->priorityLabel() }}</dd></div>
                    <div><dt class="text-gray-500">Status</dt><dd>{{ $ticket->statusLabel() }}</dd></div>
                    <div><dt class="text-gray-500">Dibuat</dt><dd>{{ $ticket->created_at->format('d M Y H:i') }}</dd></div>
                    <div><dt class="text-gray-500">Diperbarui</dt><dd>{{ $ticket->updated_at->format('d M Y H:i') }}</dd></div>
                </dl>

                <div class="flex items-center gap-4 pt-2">
                    <a href="{{ route('tickets.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Kembali</a>

                    @can('cancel', $ticket)
                        <form method="POST" action="{{ route('tickets.cancel', $ticket) }}"
                              onsubmit="return confirm('Batalkan ticket ini?')">
                            @csrf
                            @method('PATCH')
                            <x-danger-button>Batalkan Ticket</x-danger-button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>