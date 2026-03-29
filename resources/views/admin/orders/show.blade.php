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
                                    @php
                                        $imeiStr = $order->imeis->pluck('imei')->implode(', ');
                                        $phone = $order->whatsapp;
                                        if(str_starts_with($phone, '08')) {
                                            $phone = '62' . substr($phone, 1);
                                        } elseif(str_starts_with($phone, '8')) {
                                            $phone = '62' . $phone;
                                        }
                                        $waMsg = "Halo kak {$order->user->name},\n\nPesanan Anda via IMEI Lin:\n*Kode Pesanan: {$order->order_code}*\nLayanan: *{$order->service?->name}*\nBrand/Tipe: *{$order->brand} {$order->device}*\nIMEI: *{$imeiStr}*\nStatus: *" . ucwords($order->status) . "*\n\nTerima kasih!";
                                        $waLink = "https://wa.me/{$phone}?text=" . urlencode($waMsg);
                                    @endphp
                                    <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-1 text-green-600 dark:text-green-400 hover:text-green-700 font-medium hover:underline bg-green-50 dark:bg-green-900/30 px-2 py-1 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                          <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.1.133 1.396 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                                        </svg>
                                        {{ $order->whatsapp }} (Kirim Info Status)
                                    </a>
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
                                @php
                                    $pfUrl = route('storage.file', ['path' => $order->payment_proof]);
                                @endphp
                                <div class="mb-6">
                                    <button type="button" onclick="openImageModal('{{ $pfUrl }}')" title="Klik untuk melihat gambar penuh" class="block focus:outline-none text-left w-full max-w-xs">
                                        <img src="{{ $pfUrl }}" alt="Bukti Transfer" class="w-full rounded shadow hover:opacity-90 transition cursor-pointer border dark:border-gray-600">
                                    </button>
                                    <button type="button" onclick="openImageModal('{{ $pfUrl }}')" class="text-xs text-indigo-500 hover:underline mt-2 inline-block focus:outline-none shrink-0">🔍 Lihat Gambar Penuh di Modal</button>
                                </div>
                            @else
                                <div class="mb-6 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded">
                                    <p class="text-yellow-600 dark:text-yellow-400 italic text-sm">⚠️ Tidak ada bukti pembayaran yang diupload.</p>
                                </div>
                            @endif

                            <h4 class="text-lg font-bold mb-4 border-b pb-2">Screenshot IMEI</h4>
                            @if($order->screenshot_imei)
                                @php
                                    $siUrl = route('storage.file', ['path' => $order->screenshot_imei]);
                                @endphp
                                <div class="mb-6">
                                    <button type="button" onclick="openImageModal('{{ $siUrl }}')" title="Klik untuk melihat gambar penuh" class="block focus:outline-none text-left w-full max-w-xs">
                                        <img src="{{ $siUrl }}" alt="Screenshot IMEI" class="w-full rounded shadow hover:opacity-90 transition cursor-pointer border dark:border-gray-600">
                                    </button>
                                    <button type="button" onclick="openImageModal('{{ $siUrl }}')" class="text-xs text-indigo-500 hover:underline mt-2 inline-block focus:outline-none shrink-0">🔍 Lihat Gambar Penuh di Modal</button>
                                </div>
                            @else
                                <div class="mb-6 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded">
                                    <p class="text-yellow-600 dark:text-yellow-400 italic text-sm">⚠️ Tidak ada screenshot IMEI yang diupload.</p>
                                </div>
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

    <!-- Image Preview Modal -->
    <div id="imageModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-80 hidden backdrop-blur-sm transition-opacity" onclick="closeImageModal()">
        <button class="absolute top-4 right-6 text-white hover:text-gray-300 text-4xl font-bold transition focus:outline-none" onclick="closeImageModal()">&times;</button>
        <div class="relative max-w-[90%] max-h-[90vh]" onclick="event.stopPropagation()">
            <img id="modalImg" src="" class="max-w-full max-h-[90vh] rounded shadow-2xl object-contain transition-transform transform scale-95" alt="Preview Gambar">
        </div>
    </div>

    <script>
        function openImageModal(url) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImg');
            img.src = url;
            modal.classList.remove('hidden');
            setTimeout(() => img.classList.replace('scale-95', 'scale-100'), 50);
        }
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImg');
            img.classList.replace('scale-100', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 150);
        }
    </script>
</x-app-layout>
