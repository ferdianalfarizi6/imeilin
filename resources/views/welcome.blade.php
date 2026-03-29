<x-front-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4 text-gray-900 dark:text-gray-100">IMEI Lin Store</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">Pesan layanan IMEI Unlock Terbaik dan Tercepat.</p>
            
            <div class="grid md:grid-cols-2 gap-6 mt-6">
                @foreach($services as $service)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md border dark:border-gray-700 hover:shadow-lg transition">
                    <h3 class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">{{ $service->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $service->description }}</p>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        Rp {{ number_format($service->price, 0, ',', '.') }}
                    </div>
                    <a href="{{ route('order.create', ['service_id' => $service->id]) }}" class="block text-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                        Pesan Sekarang
                    </a>
                </div>
                @endforeach
            </div>

            <div class="mt-12 text-gray-600 dark:text-gray-400">
                <p>Mau dapet Gratis Unblock IMEI? <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Daftar sekarang</a> dan ajak temanmu!</p>
            </div>
        </div>
    </div>
</x-front-layout>
