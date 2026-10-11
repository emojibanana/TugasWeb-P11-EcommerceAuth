<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-100 text-red-700">Admin only — middleware `admin`</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-5 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Users</div>
                    <div class="text-3xl font-extrabold">{{ $stats['users'] }}</div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Products</div>
                    <div class="text-3xl font-extrabold">{{ $stats['products'] }}</div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Categories</div>
                    <div class="text-3xl font-extrabold">{{ $stats['categories'] }}</div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Orders</div>
                    <div class="text-3xl font-extrabold">{{ $stats['orders'] }}</div>
                </div>
                <div class="bg-white p-5 rounded-lg shadow">
                    <div class="text-sm text-gray-500">Revenue (delivered)</div>
                    <div class="text-xl font-extrabold">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-1">Stok menipis (&lt; 10)</h3>
                    <p class="text-xs text-gray-400 mb-3">Eager loading <code>with('category')</code> — tanpa N+1</p>
                    <div class="divide-y">
                        @forelse ($lowStock as $p)
                            <div class="py-2 flex items-center justify-between text-sm">
                                <div>
                                    <div class="font-semibold">{{ $p->name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $p->category->name ?? '-' }}</div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Sisa {{ $p->stock }}</span>
                                    <a href="{{ route('products.edit', $p) }}" class="text-blue-600 underline text-xs">Restock</a>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">Semua stok aman.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-1">Order terbaru</h3>
                    <p class="text-xs text-gray-400 mb-3">Eager loading <code>with(['user','items.product'])</code></p>
                    <div class="divide-y">
                        @foreach ($latestOrders as $o)
                            <a href="{{ route('orders.show', $o) }}" class="py-2 flex items-center justify-between text-sm hover:bg-gray-50 rounded px-1">
                                <div>
                                    <div class="font-semibold">#{{ $o->id }} — {{ $o->user->name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $o->items->count() }} item</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold">Rp {{ number_format($o->total, 0, ',', '.') }}</div>
                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full
                                        {{ $o->status === 'delivered' ? 'bg-green-100 text-green-700' : ($o->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ ucfirst($o->status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
