<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order #{{ $order->id }}</h2>
            <span class="text-[11px] font-semibold px-3 py-1 rounded-full
                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-700' : ($order->status === 'pending' ? 'bg-gray-100 text-gray-700' : 'bg-amber-100 text-amber-700')) }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Pemesan</div>
                        <div class="font-bold">{{ $order->user->name }}</div>
                        <div class="text-gray-500">{{ $order->user->email }}</div>
                    </div>
                    <div class="sm:text-right">
                        <div class="text-gray-500">Total</div>
                        <div class="text-2xl font-extrabold">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                        <div class="text-gray-500 text-xs">{{ $order->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-600 bg-gray-50 rounded p-3">
                    <span class="font-semibold">Alamat kirim:</span> {{ $order->shipping_address }}
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold mb-3">Item ({{ $order->items->count() }})</h3>
                <div class="divide-y">
                    @foreach ($order->items as $item)
                        <div class="py-2 flex items-center justify-between text-sm">
                            <div class="font-semibold">{{ $item->product->name ?? 'produk #' . $item->product_id }} <span class="text-gray-400">× {{ $item->quantity }}</span></div>
                            <div class="font-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('orders.index') }}" class="inline-block mt-4 text-sm underline">Kembali ke daftar</a>
            </div>
        </div>
    </div>
</x-app-layout>
