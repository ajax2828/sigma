<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('achievements') || ! Schema::hasColumn('achievements', 'description')) {
            return;
        }

        // Tidak pakai ->change(): proyek ini tidak memasang doctrine/dbal.
        // MySQL TEXT hanya ~64 KB, LONGTEXT ~4 GB. SQLite TEXT sudah tanpa
        // batas praktis, dan Postgres sudah memakai TEXT.
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE achievements MODIFY description LONGTEXT NULL');
        }
    }

    public function down(): void
    {
        // Sengaja tidak mengecilkan kolom: deskripsi yang sudah disimpan admin
        // bisa saja lebih panjang dari TEXT dan akan terpotong/gagal.
    }
};
