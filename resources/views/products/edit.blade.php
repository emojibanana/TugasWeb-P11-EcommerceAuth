<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Produk — {{ $product->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('products.update', $product) }}" class="bg-white p-6 rounded shadow space-y-4">
                @csrf @method('PUT')
                <div>
                    <x-input-label for="name" value="Nama" />
                    <x-text-input id="name" name="name" :value="old('name', $product->name)" class="w-full" required />
                    <x-input-error :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="category_id" value="Kategori" />
                    <select id="category_id" name="category_id" class="w-full border-gray-300 rounded">
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price" value="Harga (Rp)" />
                        <x-text-input id="price" name="price" type="number" step="1000" min="1000" :value="old('price', (int) $product->price)" class="w-full" required />
                        <p class="text-xs text-gray-400 mt-1">Contoh: 150000 untuk Rp 150.000. Saat ini: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <x-input-label for="stock" value="Stok" />
                        <x-text-input id="stock" name="stock" type="number" :value="old('stock', $product->stock)" class="w-full" required />
                    </div>
                </div>
                <div>
                    <x-input-label for="description" value="Deskripsi" />
                    <textarea id="description" name="description" class="w-full border-gray-300 rounded" rows="3">{{ old('description', $product->description) }}</textarea>
                </div>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) /> Aktif
                </label>
                <div>
                    <x-primary-button>Simpan</x-primary-button>
                    <a href="{{ route('products.index') }}" class="underline text-sm ml-3">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
