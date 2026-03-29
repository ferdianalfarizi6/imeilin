<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Halo, {{ $user->name }}!</h3>
                        <p>Total Poin: <span class="font-bold text-indigo-500 text-xl">{{ $user->point }}</span></p>
                    </div>
                    @if($user->point >= 20)
                    <div class="mt-4 md:mt-0">
                        <form action="{{ route('reward.claim') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition shadow-lg">
                                Tukar Reward (20 Poin)
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-lg font-bold mb-4">Sistem Referral</h4>
                        <p class="mb-2">Ajak teman daftar dengan kode rujukanmu. <br>Kamu akan mendapat <span class="text-indigo-500 font-bold">1 Poin</span> ketika pesanan temanmu telah berhasil diselesaikan.</p>
                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Kumpulkan 20 Poin dan tukarkan dengan 1 gratis biaya unblok IMEI.</p>
                        
                        <div class="mb-4">
                            <label class="block text-sm text-gray-500">Kode Referral Kamu:</label>
                            <input type="text" readonly value="{{ $user->referral_code }}" class="bg-gray-100 dark:bg-gray-700 block w-full rounded mt-1 border-gray-300 dark:border-gray-600 font-mono text-center text-lg">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-500">Link Referral:</label>
                            <div class="flex">
                                <input type="text" readonly value="{{ url('/register?ref=' . $user->referral_code) }}" class="bg-gray-100 dark:bg-gray-700 block w-full rounded-l mt-1 border-gray-300 dark:border-gray-600 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h4 class="text-lg font-bold mb-4">Teman yang Diajak ({{ $referrals->count() }})</h4>
                        @if($referrals->count() > 0)
                            <ul class="list-disc list-inside space-y-2">
                                @foreach($referrals as $ref)
                                    <li class="flex items-center">
                                        <span>{{ $ref->referredUser->name }} <span class="text-xs text-gray-500 dark:text-gray-400">({{ $ref->created_at->format('d/m/Y') }})</span></span>
                                        @if($ref->is_rewarded)
                                            <span class="text-[10px] text-green-700 font-bold rounded bg-green-100 px-2 py-0.5 ml-2">Poin Diterima</span>
                                        @else
                                            <span class="text-[10px] text-yellow-700 font-bold rounded bg-yellow-100 px-2 py-0.5 ml-2">Menunggu Pesanan</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Belum ada teman yang diajak.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h4 class="text-lg font-bold mb-4">Riwayat Pesanan</h4>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="p-3">Kode Pesanan</th>
                                    <th class="p-3">Layanan</th>
                                    <th class="p-3">Harga</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Waktu</th>
                                    <th class="p-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="p-3 font-mono text-xs">{{ $order->order_code }}</td>
                                        <td class="p-3">{{ $order->service->name }}</td>
                                        <td class="p-3">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                        <td class="p-3">
                                            @php
                                                $badgeClass = 'bg-gray-100 text-gray-800';
                                                if($order->status == 'verifikasi pembayaran') $badgeClass = 'bg-yellow-100 text-yellow-700';
                                                elseif($order->status == 'pembayaran di terima') $badgeClass = 'bg-blue-100 text-blue-700';
                                                elseif($order->status == 'proses unblok imei') $badgeClass = 'bg-purple-100 text-purple-700';
                                                elseif($order->status == 'imei berhasil di unblok') $badgeClass = 'bg-green-100 text-green-700';
                                                elseif($order->status == 'pesanan selesai') $badgeClass = 'bg-emerald-200 text-emerald-800';
                                            @endphp
                                            <span class="px-2 py-1 rounded text-xs font-medium {{ $badgeClass }}">
                                                {{ ucwords($order->status) }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                        <td class="p-3">
                                            <a href="{{ route('order.success', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400 text-xs underline">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada pesanan.</td>
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
