<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

// Tugas P11 No.7 — Policy otorisasi edit/delete.
// Di slide disebut "PostPolicy"; di domain e-commerce ini setara dengan
// ProductPolicy (produk = resource yang diotorisasi). Logika mengacu slide hal.57:
// admin = semua data, editor = edit semua post, user = hanya miliknya.
// Karena Product tidak punya user_id, aturan owner diganti role-based:
// view = semua role, create/update = admin+editor, delete = admin only.
class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // semua user login boleh lihat katalog
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor'], true);
    }

    public function update(User $user, Product $product): bool
    {
        // analog slide: $u->id === $p->user_id || $u->role === 'editor'
        // + admin selalu boleh
        return $user->role === 'admin' || $user->role === 'editor';
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }
}
