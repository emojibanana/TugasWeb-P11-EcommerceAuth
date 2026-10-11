<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Katalog Produk</h2>
            @php($role = auth()->user()->role)
            <span class="text-xs font-semibold px-3 py-1 rounded-full
                {{ $role === 'admin' ? 'bg-red-100 text-red-700' : ($role === 'editor' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                Mode: {{ ucfirst($role) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div>
            @endif

            {{-- Banner per peran --}}
            @if (auth()->user()->role === 'user')
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded text-sm">
                    Anda login sebagai <strong>User</strong> — silakan belanja. Tombol Edit/Hapus disembunyikan dan
                    ditolak Policy bila URL ditebak langsung (403).
                </div>
            @elseif (auth()->user()->role === 'editor')
                <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded text-sm">
                    Anda login sebagai <strong>Editor</strong> — boleh mengubah produk, tetapi <strong>tidak boleh menghapus</strong> (hapus khusus admin).
                </div>
            @else
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded text-sm">
                    Anda login sebagai <strong>Admin</strong> — akses penuh: ubah dan hapus produk, plus
                    <a href="{{ route('admin.dashboard') }}" class="underline font-semibold">Admin Dashboard</a>.
                </div>
            @endif

            <form method="GET" class="mb-6 flex gap-2">
                <x-text-input name="search" :value="request('search')" placeholder="Cari produk, mis. laptop, serum, tenda..." class="w-full" />
                <x-primary-button>Cari</x-primary-button>
            </form>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($products as $product)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden flex flex-col">
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex items-start justify-between gap-2">
                                <div class="font-bold text-gray-900 leading-snug">{{ $product->name }}</div>
                                <span class="shrink-0 text-[11px] font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">{{ $product->category->name ?? '-' }}</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $product->description }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <div class="text-lg font-extrabold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                @if ($product->stock < 10)
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Sisa {{ $product->stock }}</span>
                                @elseif ($product->stock < 50)
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Stok {{ $product->stock }}</span>
                                @else
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Ready {{ $product->stock }}</span>
                                @endif
                            </div>
                            {{-- Tombol disembunyikan di view HANYA untuk UX, keamanan tetap di Policy (slide hal.56) --}}
                            <div class="mt-3 pt-3 border-t flex gap-2 text-sm">
                                @can('update', $product)
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700">Edit</a>
                                @endcan
                                @can('delete', $product)
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus {{ $product->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="inline-flex items-center px-3 py-1.5 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded shadow text-center text-gray-500">
                        Tidak ada produk ditemukan untuk pencarian "{{ request('search') }}".
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>
