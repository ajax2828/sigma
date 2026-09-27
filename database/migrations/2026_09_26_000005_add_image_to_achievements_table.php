<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kartu prestasi di landing memakai gambar di atas, jadi butuh kolom image.
        if (! Schema::hasColumn('achievements', 'image')) {
            Schema::table('achievements', function (Blueprint $table) {
                $table->string('image')->nullable()->after('icon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('achievements', 'image')) {
            Schema::table('achievements', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
