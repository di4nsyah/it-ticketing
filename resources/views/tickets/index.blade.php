<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ auth()->user()->isTeknisi() ? 'Semua Ticket' : 'Ticket Saya' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Judul</th>
                            @if (auth()->user()->isTeknisi())
                                <th class="px-4 py-3">Pembuat</th>
                            @endif
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Prioritas</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Dibuat</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td class="px-4 py-3">{{ $ticket->title }}</td>
                                @if (auth()->user()->isTeknisi())
                                    <td class="px-4 py-3">{{ $ticket->user->name }}</td>
                                @endif
                                <td class="px-4 py-3">{{ $ticket->category->name }}</td>
                                <td class="px-4 py-3">{{ $ticket->priorityLabel() }}</td>
                                <td class="px-4 py-3">{{ $ticket->statusLabel() }}</td>
                                <td class="px-4 py-3">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada ticket.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $tickets->links() }}</div>
        </div>
    </div>
</x-app-layout>