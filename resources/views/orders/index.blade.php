<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ auth()->user()->role === 'user' ? 'Pesanan Saya' : 'Semua Pesanan' }}
            </h2>
            <span class="text-xs text-gray-500">{{ $orders->total() }} order</span>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (auth()->user()->role === 'user')
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded text-sm">
                    Menampilkan <strong>hanya pesanan milik Anda</strong> (OrderPolicy). Pesanan orang lain tidak bisa dibuka langsung via URL.
                </div>
            @else
                <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded text-sm">
                    Anda login sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong> — dapat melihat semua pesanan (OrderPolicy).
                </div>
            @endif
            <div class="bg-white rounded-lg shadow divide-y">
                @forelse ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
                        <div>
                            <div class="font-bold">#{{ $order->id }}</div>
                            <div class="text-xs text-gray-500">{{ $order->items->count() }} item — {{ $order->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-extrabold">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : ($order->status === 'pending' ? 'bg-gray-100 text-gray-700' : 'bg-amber-100 text-amber-700')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-500">Belum ada pesanan. <a href="{{ route('products.index') }}" class="underline">Lihat katalog</a>.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
