<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesan Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Layanan -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Layanan</label>
                            <select name="service_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ $selectedService == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Brand & Device -->
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Merek HP</label>
                                <input type="text" name="brand" required placeholder="Contoh: Apple" value="{{ old('brand') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                @error('brand') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe / Model</label>
                                <input type="text" name="device" required placeholder="Contoh: iPhone 13 Pro" value="{{ old('device') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                @error('device') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- IMEIs -->
                        <div class="mb-4" id="imei-container">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor IMEI</label>
                            <div class="flex items-center gap-2 mt-1 imei-row">
                                <input type="text" name="imeis[]" required placeholder="Masukkan 15 Digit IMEI" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                <button type="button" class="add-imei bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-3 rounded">+</button>
                            </div>
                            @error('imeis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            @error('imeis.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor WhatsApp</label>
                            <input type="text" name="whatsapp" required value="{{ old('whatsapp', auth()->user()->whatsapp) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            <p class="text-xs text-gray-500 mt-1">Gunakan format 08xxx</p>
                            @error('whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Screenshot IMEI -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Screenshot IMEI</label>
                            <p class="text-xs text-gray-500 mt-1 mb-2">Wajib mengunggah 1 bukti screenshot IMEI yang sesuai (misal ketik *#06#).</p>
                            <input type="file" name="screenshot_imei" required accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:text-gray-300 dark:file:bg-gray-700 dark:file:text-gray-200">
                            @error('screenshot_imei') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Rekening Pembayaran -->
                        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md">
                            <h4 class="font-bold text-yellow-800 dark:text-yellow-200 mb-2">Informasi Pembayaran</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 whitespace-pre-wrap">{{ \App\Models\Setting::get('bank_account', 'Belum Diatur') }}</p>
                            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-2 font-bold">Harap transfer sesuai harga layanan lalu upload bukti di bawah.</p>
                        </div>

                        <!-- Payment Proof -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Bukti Transfer</label>
                            <input type="file" name="payment_proof" required accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-300 dark:file:bg-indigo-900 dark:file:text-indigo-200">
                            @error('payment_proof') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded transition">
                                Pesan Sekarang
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('imei-container');
            
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-imei')) {
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-2 mt-2 imei-row';
                    row.innerHTML = `
                        <input type="text" name="imeis[]" required placeholder="Masukkan 15 Digit IMEI" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        <button type="button" class="remove-imei bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-3 rounded">-</button>
                    `;
                    container.appendChild(row);
                }
                if (e.target.classList.contains('remove-imei')) {
                    e.target.closest('.imei-row').remove();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
