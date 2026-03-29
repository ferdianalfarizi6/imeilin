<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Layanan: ') }} {{ $service->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('admin.services.index') }}" class="text-sm text-indigo-500 hover:underline mb-4 inline-block">&larr; Batal & Kembali</a>

                    <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Nama Layanan</label>
                            <input type="text" name="name" required value="{{ old('name', $service->name) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Harga (Rp)</label>
                            <input type="number" name="price" required value="{{ old('price', $service->price) }}" min="0" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Status Publikasi</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="rounded border-gray-300 dark:bg-gray-700">
                                    <span class="text-sm">Tampilkan secara publik (Aktif)</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">Deskripsi Singkat</label>
                            <textarea name="description" rows="3" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('description', $service->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                            Perbarui Layanan
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
