<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-500 hover:underline inline-block">&larr; Kembali ke Dashboard</a>

                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-4">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <div class="flex border rounded overflow-hidden">
                            <a href="{{ route('admin.orders.index', ['tab' => 'ongoing', 'search' => request('search')]) }}" class="px-4 py-2 text-sm font-medium {{ $tab === 'ongoing' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                Ongoing
                            </a>
                            <a href="{{ route('admin.orders.index', ['tab' => 'history', 'search' => request('search')]) }}" class="px-4 py-2 text-sm font-medium {{ $tab === 'history' ? 'bg-indigo-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                                History
                            </a>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari order, imei, nama, WA..." class="rounded border-gray-300 text-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded text-sm hidden md:block">Cari</button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="p-3">Kode Pesanan</th>
                                    <th class="p-3">Pelanggan</th>
                                    <th class="p-3">Layanan</th>
                                    <th class="p-3 text-center">Harga</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3">Waktu Dibuat</th>
                                    <th class="p-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="p-3 font-mono text-xs">{{ $order->order_code }}</td>
                                    <td class="p-3">
                                        <p class="font-bold">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->whatsapp }}</p>
                                    </td>
                                    <td class="p-3">{{ optional($order->service)->name ?? '-' }}</td>
                                    <td class="p-3 text-center text-xs">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">
                                        @php
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            if($order->status == 'verifikasi pembayaran') $badgeClass = 'bg-yellow-100 text-yellow-700';
                                            elseif($order->status == 'pembayaran di terima') $badgeClass = 'bg-blue-100 text-blue-700';
                                            elseif($order->status == 'proses unblok imei') $badgeClass = 'bg-purple-100 text-purple-700';
                                            elseif($order->status == 'imei berhasil di unblok') $badgeClass = 'bg-green-100 text-green-700';
                                            elseif($order->status == 'pesanan selesai') $badgeClass = 'bg-emerald-200 text-emerald-800';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-medium inline-block {{ $badgeClass }}">
                                            {{ ucwords($order->status) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-3">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-block bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-1 px-3 mt-1 rounded text-xs">Kelola</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center text-gray-500">Belum ada pesanan ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
