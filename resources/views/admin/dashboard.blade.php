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
    </div>
</x-app-layout>
