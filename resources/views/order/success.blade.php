<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail & Lacak Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center border-b pb-4 mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Kode Pesanan: <span class="text-indigo-600 dark:text-indigo-400 font-mono">{{ $order->order_code }}</span></h3>
                            <p class="text-sm text-gray-500 mt-1">Dibuat pada: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @php
                                $badgeClass = 'bg-gray-100 text-gray-800';
                                if($order->status == 'verifikasi pembayaran') $badgeClass = 'bg-yellow-100 text-yellow-700';
                                elseif($order->status == 'pembayaran di terima') $badgeClass = 'bg-blue-100 text-blue-700';
                                elseif($order->status == 'proses unblok imei') $badgeClass = 'bg-purple-100 text-purple-700';
                                elseif($order->status == 'imei berhasil di unblok') $badgeClass = 'bg-green-100 text-green-700';
                                elseif($order->status == 'pesanan selesai') $badgeClass = 'bg-emerald-200 text-emerald-800';
                            @endphp
                            <span class="px-4 py-2 rounded-full text-sm font-bold shadow-sm inline-block {{ $badgeClass }}">
                                {{ ucwords($order->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress Tracker Component -->
                    <div class="mb-10 mt-8 relative">
                        <div class="flex items-center justify-between">
                            @php
                                $statusIndex = array_search($order->status, $statuses);
                            @endphp
                            @foreach($statuses as $index => $st)
                                @php
                                    $isCompleted = $index <= $statusIndex;
                                    $isCurrent = $index == $statusIndex;
                                    $bgColor = $isCompleted ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700';
                                    $textColor = $isCompleted ? 'text-indigo-600 font-bold' : 'text-gray-400';
                                    $ripple = $isCurrent && $index < count($statuses)-1 ? 'animate-pulse ring-4 ring-indigo-200 dark:ring-indigo-900' : '';
                                @endphp
                                <div class="text-center relative z-10 w-1/5">
                                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center text-white text-sm shadow-sm transition-all duration-300 {{ $bgColor }} {{ $ripple }}">
                                        @if($isCompleted && !$isCurrent)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <div class="mt-3 text-[10px] md:text-sm leading-tight {{ $textColor }} hidden md:block px-2">
                                        {{ ucwords($st) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="absolute top-[20px] left-0 w-full h-1.5 bg-gray-200 dark:bg-gray-700 -z-0 rounded px-5 box-border" style="transform: translateY(-50%);">
                            <div class="h-full bg-indigo-600 rounded transition-all duration-500 ease-in-out" style="width: {{ $statusIndex == 0 ? 0 : ($statusIndex / (count($statuses) - 1)) * 100 }}%"></div>
                        </div>
                    </div>

                    @if($order->status == 'verifikasi pembayaran')
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-5 rounded-r mt-6 mb-8 text-yellow-800 dark:text-yellow-200 flex flex-col md:flex-row gap-4 items-center">
                        <div class="flex-grow">
                            <h4 class="font-bold text-lg mb-1">Pesanan Menunggu Verifikasi</h4>
                            <p class="text-sm border-gray-100">Kami telah menerima pesananmu. Jika belum, segera konfirmasi pengiriman pesananmu via WhatsApp agar segera diproses Admin.</p>
                        </div>
                        <div class="flex-shrink-0 w-full md:w-auto text-center">
                            <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg text-sm transition shadow shadow-green-200 dark:shadow-none whitespace-nowrap w-full">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Chat Admin
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($order->admin_note)
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 p-5 rounded-lg mb-8 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="text-indigo-500 mt-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-gray-200 text-sm uppercase tracking-wider mb-1">Pesan / Catatan Admin</h4>
                                <p class="text-gray-700 dark:text-gray-300">{{ $order->admin_note }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="grid md:grid-cols-2 gap-8 text-gray-800 dark:text-gray-200 mt-4">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <h4 class="font-bold text-lg mb-4 flex items-center gap-2 border-b pb-2 dark:border-gray-600">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                Rincian Layanan
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <span class="text-gray-500">Layanan:</span>
                                    <span class="font-semibold text-right">{{ $order->service->name }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <span class="text-gray-500">Merek & Tipe:</span>
                                    <span class="font-semibold text-right">{{ $order->brand }} / {{ $order->device }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <span class="text-gray-500">Total Tagihan:</span>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400 text-right">Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="text-gray-500">Waktu Terakhir Diproses:</span>
                                    <span class="font-semibold text-right">{{ $order->processed_at ? $order->processed_at->format('d/m/y H:i') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <h4 class="font-bold text-lg mb-4 flex items-center gap-2 border-b pb-2 dark:border-gray-600">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Unit Diproses ({{ collect($order->imeis)->count() }})
                            </h4>
                            <div class="space-y-2">
                                @foreach($order->imeis as $imei)
                                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 px-4 py-2 rounded font-mono text-sm shadow-sm flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full @if(in_array($order->status, ['imei berhasil di unblok', 'pesanan selesai'])) bg-green-500 @else bg-gray-300 dark:bg-gray-600 @endif"></div>
                                        {{ $imei->imei }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 text-center pt-6 border-t dark:border-gray-700">
                        <a href="{{ route('dashboard') }}" class="text-indigo-500 hover:text-indigo-600 font-medium inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Dashboard
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
