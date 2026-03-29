<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Data Cards -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
                    <h3 class="text-gray-500 dark:text-gray-400 font-bold mb-2 uppercase text-xs">Total Pesanan</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalOrders }}</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
                    <h3 class="text-gray-500 dark:text-gray-400 font-bold mb-2 uppercase text-xs">Total Pengguna</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center justify-center">
                    <h3 class="text-gray-500 dark:text-gray-400 font-bold mb-2 uppercase text-xs">Total Pendapatan</h3>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Graph Area / Admin Menu -->
            <div class="grid md:grid-cols-2 gap-6 mt-6">
               <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-lg font-bold mb-4">Grafik Pendapatan (7 Hari Terakhir)</h4>
                        
                        <div class="h-64 flex items-end gap-2 border-b border-l border-gray-300 dark:border-gray-600 p-2 pb-0 overflow-x-auto">
                            @php
                                $maxIncome = max(array_column($graphData, 'income')) ?: 1; // avoid div 0
                            @endphp
                            
                            @foreach($graphData as $data)
                                @php
                                    $heightPercent = ($data['income'] / $maxIncome) * 100;
                                    $heightPercent = max($heightPercent, 2); // min 2% visible
                                @endphp
                                <div class="flex flex-col items-center flex-1 justify-end h-full">
                                    <div class="w-full bg-indigo-500 rounded-t transition-all hover:bg-indigo-600 relative group" style="height: {{ $heightPercent }}%;">
                                        <div class="absolute bottom-full mb-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded hidden group-hover:block whitespace-nowrap z-10">
                                            Rp {{ number_format($data['income'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="text-[10px] text-gray-500 mt-2 truncate max-w-full text-center">{{ date('d/m', strtotime($data['date'])) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                     <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                         <h4 class="text-lg font-bold mb-4">Navigasi Admin</h4>
                         
                         <a href="{{ route('admin.orders.index') }}" class="block w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 p-4 rounded-lg font-semibold transition flex justify-between items-center">
                             <span>📦 Manajemen Pesanan</span>
                             <span>&rarr;</span>
                         </a>
                         
                         <a href="{{ route('admin.services.index') }}" class="block w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 p-4 rounded-lg font-semibold transition flex justify-between items-center">
                             <span>🛠️ Manajemen Layanan</span>
                             <span>&rarr;</span>
                         </a>

                         <a href="{{ route('admin.users') }}" class="block w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 p-4 rounded-lg font-semibold transition flex justify-between items-center">
                             <span>👥 Manajemen Pengguna</span>
                             <span>&rarr;</span>
                         </a>

                         <a href="{{ route('admin.settings.index') }}" class="block w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 p-4 rounded-lg font-semibold transition flex justify-between items-center mt-2">
                             <span>⚙️ Pengaturan Pembayaran</span>
                             <span>&rarr;</span>
                         </a>
                     </div>
                </div>
            </div>

        </div>

        <!-- Recent Orders with IMEI -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4 border-b dark:border-gray-700 pb-3">
                        <h4 class="text-lg font-bold">Data IMEI Pesanan Terbaru</h4>
                        <a href="{{ route('admin.orders.index') }}" class="text-xs text-indigo-500 hover:underline">Kelola Semua Pesanan &rarr;</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="p-3">Kode Pesanan</th>
                                    <th class="p-3">Pelanggan</th>
                                    <th class="p-3">Data IMEI</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $ro)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="p-3 font-mono text-xs">{{ $ro->order_code }}</td>
                                    <td class="p-3 font-bold">{{ optional($ro->user)->name }}</td>
                                    <td class="p-3">
                                        @foreach($ro->imeis as $imei)
                                            <div class="font-mono text-xs">{{ $imei->imei }}</div>
                                        @endforeach
                                        @if($ro->imeis->isEmpty())
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        @php
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            if($ro->status == 'verifikasi pembayaran') $badgeClass = 'bg-yellow-100 text-yellow-700';
                                            elseif($ro->status == 'pembayaran di terima') $badgeClass = 'bg-blue-100 text-blue-700';
                                            elseif($ro->status == 'proses unblok imei') $badgeClass = 'bg-purple-100 text-purple-700';
                                            elseif($ro->status == 'imei berhasil di unblok') $badgeClass = 'bg-green-100 text-green-700';
                                            elseif($ro->status == 'pesanan selesai') $badgeClass = 'bg-emerald-200 text-emerald-800';
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-[10px] font-medium inline-block {{ $badgeClass }}">
                                            {{ ucwords($ro->status) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('admin.orders.show', $ro->id) }}" class="text-indigo-500 hover:underline text-xs bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-full">Kelola</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada pesanan terbaru.</td>
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
