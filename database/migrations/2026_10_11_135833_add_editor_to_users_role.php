<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambah role 'editor' ke enum users.role (Bagian B No.6 multi-role).
     */
    public function up(): void
    {
        // SQLite (dipakai phpunit :memory:) tidak mengenal MODIFY ENUM
        // dan tidak menegakkan enum — lewati agar test tetap jalan.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','editor','user') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        // Kembalikan: paksa role editor -> user dulu agar downgrade aman
        DB::table('users')->where('role', 'editor')->update(['role' => 'user']);
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};
