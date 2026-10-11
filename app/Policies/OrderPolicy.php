<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

// Otorisasi order: user hanya boleh akses order miliknya,
// admin boleh semua, editor boleh lihat semua tapi tidak hapus.
class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->role === 'admin'
            || $user->role === 'editor'
            || $user->id === $order->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->role === 'admin'
            || $user->id === $order->user_id;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }
}
