<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard') }}</h2>
            @php($role = auth()->user()->role)
            <span class="text-xs font-semibold px-3 py-1 rounded-full
                {{ $role === 'admin' ? 'bg-red-100 text-red-700' : ($role === 'editor' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                {{ ucfirst($role) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Halo, <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
                    <div class="mt-4 grid sm:grid-cols-2 gap-3 text-sm">
                        <a href="{{ route('products.index') }}" class="block p-4 rounded-lg border hover:shadow">
                            <div class="font-bold">Katalog Produk</div>
                            <div class="text-gray-500 text-xs">
                                @if ($role === 'user') Belanja & lihat harga realistis IDR
                                @elseif ($role === 'editor') Kelola (edit) produk
                                @else Kelola penuh (edit + hapus) @endif
                            </div>
                        </a>
                        <a href="{{ route('orders.index') }}" class="block p-4 rounded-lg border hover:shadow">
                            <div class="font-bold">{{ $role === 'user' ? 'Pesanan Saya' : 'Semua Pesanan' }}</div>
                            <div class="text-gray-500 text-xs">
                                @if ($role === 'user') Hanya order milik Anda (Policy)
                                @else Pantau order semua user @endif
                            </div>
                        </a>
                    </div>
                    <div class="mt-4 text-xs text-gray-500">
                        Semua akses tersedia tergantung peran Anda ehe~
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
