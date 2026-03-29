<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-500 hover:underline mb-4 inline-block">&larr; Kembali ke Dashboard</a>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="p-3">Nama Lengkap</th>
                                    <th class="p-3">Email</th>
                                    <th class="p-3">WhatsApp</th>
                                    <th class="p-3">Kode Referral</th>
                                    <th class="p-3 text-center">Referral</th>
                                    <th class="p-3 text-center">Poin</th>
                                    <th class="p-3">Tanggal Daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="p-3">{{ $user->name }}</td>
                                    <td class="p-3">{{ $user->email }}</td>
                                    <td class="p-3">{{ $user->whatsapp }}</td>
                                    <td class="p-3 font-mono text-xs">{{ $user->referral_code ?? '-' }}</td>
                                    <td class="p-3 text-center">{{ $user->referrals_count }}</td>
                                    <td class="p-3 text-center"><span class="font-bold text-indigo-500">{{ $user->point }}</span></td>
                                    <td class="p-3">{{ $user->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
