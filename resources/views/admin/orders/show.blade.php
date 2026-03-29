<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pesanan: ') }} {{ $order->order_code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-500 hover:underline mb-4 inline-block">&larr; Kembali ke Manajemen Pesanan</a>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-bold mb-6 text-gray-900 dark:text-gray-100">Progress Pesanan</h4>
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            @php
                                $statusIndex = array_search($order->status, $statuses);
                            @endphp
                            @foreach($statuses as $index => $st)
                                @php
                                    $isCompleted = $index <= $statusIndex;
                                    $isCurrent = $index == $statusIndex;
                                    $bgColor = $isCompleted ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700';
                                    $textColor = $isCompleted ? 'text-indigo-600 font-bold' : 'text-gray-500';
                                @endphp
                                <div class="text-center w-1/5 relative z-10">
                                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-white text-xs mb-2 {{ $bgColor }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="text-[10px] md:text-xs leading-tight {{ $textColor }} hidden md:block px-2">
                                        {{ ucwords($st) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="absolute top-5 left-0 w-full h-1 bg-gray-200 dark:bg-gray-700 rounded z-0" style="margin-top: -2px;">
                            <div class="h-full bg-indigo-600 rounded" style="width: {{ $statusIndex == 0 ? 10 : ($statusIndex / (count($statuses) - 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-center">
                        @php
                            $badgeClass = 'bg-gray-100 text-gray-800';
                            if($order->status == 'verifikasi pembayaran') $badgeClass = 'bg-yellow-100 text-yellow-700';
                            elseif($order->status == 'pembayaran di terima') $badgeClass = 'bg-blue-100 text-blue-700';
                            elseif($order->status == 'proses unblok imei') $badgeClass = 'bg-purple-100 text-purple-700';
                            elseif($order->status == 'imei berhasil di unblok') $badgeClass = 'bg-green-100 text-green-700';
                            elseif($order->status == 'pesanan selesai') $badgeClass = 'bg-emerald-200 text-emerald-800';
                        @endphp
                        <span class="px-4 py-2 rounded-full text-sm font-medium {{ $badgeClass }}">
                            Status Saat Ini: {{ ucwords($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-bold mb-4 border-b pb-2">Informasi Pesanan</h4>
                            
                            <table class="w-full text-sm">
                                <tr><td class="py-2 text-gray-500 font-semibold w-1/3">Waktu Pesan</td><td class="py-2">{{ $order->created_at->format('d F Y, H:i') }}</td></tr>
                                <tr><td class="py-2 text-gray-500 font-semibold w-1/3">Nama Pelanggan</td><td class="py-2">{{ $order->user->name }}</td></tr>
                                <tr><td class="py-2 text-gray-500 font-semibold w-1/3">WhatsApp</td><td class="py-2">
                                    <a href="https://wa.me/{{ $order->whatsapp }}" target="_blank" class="text-green-500 hover:underline">{{ $order->whatsapp }}</a>
                                </td></tr>
                                <tr><td class="py-2 text-gray-500 font-semibold w-1/3">Layanan</td><td class="py-2">{{ optional($order->service)->name ?? '-' }}</td></tr>
                                <tr><td class="py-2 text-gray-500 font-semibold w-1/3">Merek / Tipe</td><td class="py-2">{{ $order->brand }} / {{ $order->device }}</td></tr>
                                <tr><td class="py-2 text-gray-500 font-semibold w-1/3">Total Pembayaran</td><td class="py-2 font-bold text-indigo-500">Rp {{ number_format($order->price, 0, ',', '.') }}</td></tr>
                            </table>

                            <h4 class="text-lg font-bold mt-6 mb-4 border-b pb-2">Daftar IMEI ({{ $order->imeis->count() }})</h4>
                            <ul class="list-disc list-inside space-y-1 mb-6 font-mono bg-gray-50 dark:bg-gray-700 p-3 rounded">
                                @foreach($order->imeis as $imei)
                                    <li>{{ $imei->imei }}</li>
                                @endforeach
                            </ul>
                            
                            <h4 class="text-lg font-bold mb-4 border-b pb-2">Bukti Pembayaran</h4>
                            @if($order->payment_proof)
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" class="w-full max-w-xs object-cover rounded shadow mb-6 hover:opacity-90 transition cursor-pointer">
                                </a>
                            @else
                                <p class="text-yellow-600 mb-6 italic">Tidak ada bukti pembayaran (Gratis/Reward).</p>
                            @endif
                        </div>

                        <div>
                            <h4 class="text-lg font-bold mb-4 border-b pb-2">Update Status & Catatan</h4>
                            
                            <div class="mb-6 flex flex-wrap gap-2">
                                @if($order->status === 'verifikasi pembayaran')
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="pembayaran di terima">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-2 px-3 rounded shadow">
                                            ✅ Terima Pembayaran
                                        </button>
                                    </form>
                                @endif
                                
                                @if($order->status === 'pembayaran di terima')
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="proses unblok imei">
                                        <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white text-xs font-bold py-2 px-3 rounded shadow">
                                            ⚙️ Mulai Proses
                                        </button>
                                    </form>
                                @endif
                                
                                @if($order->status === 'proses unblok imei')
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="imei berhasil di unblok">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-3 rounded shadow">
                                            🎉 Berhasil
                                        </button>
                                    </form>
                                @endif
                                
                                @if($order->status === 'imei berhasil di unblok')
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="pesanan selesai">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 px-3 rounded shadow">
                                            🏁 Selesaikan
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded border dark:border-gray-600">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Status Pesanan</label>
                                    <select name="status" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 bg-white dark:bg-gray-800">
                                        @foreach($statuses as $index => $st)
                                            @php
                                                $disabled = ($index < $statusIndex || $index > $statusIndex + 1) ? 'disabled' : '';
                                                $selected = ($st == $order->status) ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $st }}" {{ $selected }} {{ $disabled }}>
                                                {{ ucwords($st) }} 
                                                @if($index < $statusIndex) (Selesai) @endif
                                                @if($index > $statusIndex + 1) (Terkunci) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Status hanya dapat diperbarui ke tahap selanjutnya.</p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Catatan Admin</label>
                                    <textarea name="admin_note" rows="4" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 bg-white dark:bg-gray-800" placeholder="Ketik catatan untuk pesanan ini atau pelanggan...">{{ $order->admin_note }}</textarea>
                                </div>

                                <button type="submit" class="bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white font-bold py-2 px-6 rounded transition w-full">
                                    Simpan Perubahan
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
