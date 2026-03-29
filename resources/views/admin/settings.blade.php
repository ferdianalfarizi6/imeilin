<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-500 hover:underline mb-4 inline-block">&larr; Kembali ke Dashboard</a>

                    <form action="{{ route('admin.settings.update') }}" method="POST" class="mt-4">
                        @csrf
                        
                        <!-- Rekening Bank -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">Informasi Rekening Pembayaran</label>
                            <p class="text-xs text-gray-500 mb-2">Ditampilkan di halaman pemesanan (Order Form) untuk pelanggan mentransfer dana.</p>
                            <textarea name="settings[bank_account]" rows="3" required class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 font-mono">{{ old('settings.bank_account', $settings['bank_account']) }}</textarea>
                            @error('settings.bank_account') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email Admin -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">Email Kontak Admin</label>
                            <p class="text-xs text-gray-500 mb-2">Ditampilkan di bagian footer atau hubungi kami (jika diperlukan).</p>
                            <input type="email" name="settings[admin_email]" required value="{{ old('settings.admin_email', $settings['admin_email']) }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            @error('settings.admin_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- WhatsApp Admin -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">Nomor WhatsApp Admin</label>
                            <p class="text-xs text-gray-500 mb-2">Digunakan untuk menerima notifikasi pesanan baru (Mulai dengan kode negara, contoh: 628...).</p>
                            <input type="text" name="settings[admin_whatsapp]" required value="{{ old('settings.admin_whatsapp', $settings['admin_whatsapp'] ?? '628') }}" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            @error('settings.admin_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                            Simpan Pengaturan
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
