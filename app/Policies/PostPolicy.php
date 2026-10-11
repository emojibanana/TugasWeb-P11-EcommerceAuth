<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

// Referensi slide P11 hal.56-57 (Gates & Policies):
//   public function update(User $u, Post $p): bool {
//       return $u->id === $p->user_id || $u->role === 'editor';
//   }
// Policy ini adalah implementasi generik pola tersebut (duck-typed agar bisa
// dipakai untuk model apapun yang punya user_id, mis. jika nanti ada model Post).
// Untuk e-commerce, policy produksi yang terdaftar adalah ProductPolicy.
class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, object $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor', 'user'], true);
    }

    public function update(User $user, object $post): bool
    {
        return $user->id === ($post->user_id ?? null)
            || in_array($user->role, ['admin', 'editor'], true);
    }

    public function delete(User $user, object $post): bool
    {
        return $user->id === ($post->user_id ?? null)
            || $user->role === 'admin';
    }
}
