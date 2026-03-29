<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-500 hover:underline inline-block">&larr; Kembali ke Dashboard</a>
                
                <a href="{{ route('admin.services.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                    + Tambah Layanan Baru
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="p-3">Nama Layanan</th>
                                    <th class="p-3">Harga</th>
                                    <th class="p-3">Deskripsi Singkat</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="p-3 font-semibold">{{ $service->name }}</td>
                                    <td class="p-3 text-indigo-500 font-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                    <td class="p-3 text-xs text-gray-500 dark:text-gray-400 whitespace-normal min-w-[200px]">{{ \Illuminate\Support\Str::limit($service->description, 50) }}</td>
                                    <td class="p-3 text-center">
                                        @if($service->is_active)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">Aktif</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td class="p-3 flex justify-center gap-2">
                                        <a href="{{ route('admin.services.edit', $service->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 text-xs rounded transition">Edit</a>
                                        
                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus layanan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 text-xs rounded transition">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada layanan. Silakan tambahkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
