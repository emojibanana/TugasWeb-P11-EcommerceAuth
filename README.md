# TR11 Ekomers — Tugas Rutin 11: E-Commerce DB + Secure Auth

Aplikasi e-commerce Laravel untuk **Tugas Rutin 11 — Database & Eloquent + Auth & Security**
(Authentication vs Authorization, Laravel Breeze, Middleware Pipeline, Gates & Policies, Multi-Role).

## Isi Sistem

**Katalog e-commerce** (113 produk, 26 kategori, harga IDR realistis) dengan 3 peran:

| Fitur | User | Editor | Admin |
|---|---|---|---|
| Lihat katalog `/products` | Ya | Ya | Ya |
| Edit produk | Tidak (403) | Ya | Ya |
| Hapus produk | Tidak (403) | Tidak (403) | Ya |
| Lihat order | Milik sendiri | Semua | Semua |
| Buka `/admin/dashboard` | 403 | 403 | Ya |
| Buka `/editor/products` | 403 | Ya | Ya |

Keamanan mengikuti prinsip slide: tombol disembunyikan di view **hanya untuk UX**,
otorisasi asli ditegakkan di **Middleware + Policy + Gate** (cek di controller).

## Bagian A — Database & Eloquent

1. **Migrasi 7 tabel + FK**: `categories` (self-FK `parent_id`), `products` → categories,
   `carts` → users + products, `orders` → users, `order_items` → orders + products,
   `reviews` → users + products, `wishlists` → users + products.
   Migrasi tambahan: `users.role` enum `admin,editor,user` dan `reviews.is_approved`.
2. **Seeder + Factory (data realistis)**: 17 user, 26 kategori (6 induk + 20 anak),
   113 produk dengan rentang harga IDR per kategori (HP 1,5–18 jt, laptop 5–35 jt,
   buku 60–400 rb, dst.), plus cart / wishlist / review / order realistis.
3. **Model + relasi + scope**: `User, Category, Product, Cart, Order, OrderItem, Review, Wishlist`;
   scope `Product::active()/inStock()`, `Category::root()`, `Order::pending()`,
   `Review::approved()`, `Cart::forUser($id)`.
4. **5 query Tinker** (bukti di `dokumentasi/Tinker1.png` – `Tinker5.png`):
   ```php
   Product::with('category')->orderByDesc('price')->limit(5)->get();
   Product::active()->count(); Product::inStock()->count();
   Category::withCount('products')->orderByDesc('products_count')->limit(5)->get();
   Order::with(['user','items.product'])->latest()->limit(3)->get();
   Product::withAvg('reviews','rating')->withCount('reviews')->orderByDesc('reviews_avg_rating')->limit(5)->get();
   ```

## Bagian B — Auth & Security

5. **Laravel Breeze**: login / register / logout, `/profile`, proteksi `verified`.
   Register baru otomatis role `user` (tidak bisa self-assign admin).
6. **Multi-role + custom middleware** (`bootstrap/app.php` alias `admin`/`role`):
   `EnsureAdmin` (hanya `admin`, selain itu 403) dan `EnsureRole` (`role:admin,editor`).
7. **Otorisasi**: `ProductPolicy` (padanan PostPolicy untuk e-commerce —
   lihat semua boleh login, ubah admin + editor, hapus hanya admin),
   `OrderPolicy` (user hanya miliknya), `PostPolicy` (logika slide verbatim
   `$u->id === $p->user_id || role editor`), Gate `admin` + `edit-products`.
8. **Proteksi route + testing incognito**: `/admin/dashboard` (auth + admin),
   `/editor/products` (auth + role), edit/hapus produk via `$this->authorize()`.
   Uji: tab biasa login admin (lolos) vs tab incognito login user (403) —
   bukti di `dokumentasi/error403.png`.

Bonus: **eager loading** di semua controller (`with('category')`,
`with(['user','items.product'])`) — demo N+1: tanpa `with()` 6 query, dengan `with()` 2 query.

## Akun Demo (password semua: `password`)

- Admin: `admin@example.com`
- Editor: `editor@example.com`
- User: 15 akun seeder atau register baru via `/register`

## Cara Menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate
# sesuaikan DB di .env (MySQL: DB_DATABASE=tr11_ekomers), lalu:
php artisan migrate:fresh --seed
npm install && npm run build
php artisan serve   # buka http://localhost:8000
php artisan test    # 25/25 lolos
```

## Screenshot (`dokumentasi/`)

| File | Isi |
|---|---|
| `Tinker1.png` – `Tinker5.png` | 5 query Tinker + hasil |
| `dashboard_admin.png`, `dashboard_editor.png`, `dashboard_user.png` | Dashboard per peran |
| `katalog_admin.png`, `katalog_editor.png`, `katalog_user.png` | Katalog per peran |
| `order_admin.png`, `order_editor.png`, `order_user.png` | Daftar order per peran |
| `error403.png` | Bukti penolakan akses (403) |

## File Penting

- `database/migrations/` — 7 tabel e-commerce + fix `users.role` + `reviews.is_approved`
- `app/Models/` — 8 model + relasi + scope
- `database/{factories,seeders}/` — data realistis IDR
- `app/Http/Middleware/{EnsureAdmin,EnsureRole}.php` — middleware multi-role
- `app/Policies/{ProductPolicy,OrderPolicy,PostPolicy}.php` — otorisasi
- `app/Providers/AppServiceProvider.php` — Gate `admin`, `edit-products`
- `app/Http/Controllers/{ProductController,OrderController,Admin/DashboardController}.php`
- `routes/web.php` — route terproteksi middleware + policy

## Tech Stack

Laravel 13, PHP 8.3, MySQL, Breeze (Blade), Tailwind CSS, PHPUnit.
