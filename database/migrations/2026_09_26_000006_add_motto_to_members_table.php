<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Card member menampilkan "kata motivasi"; description lama berisi bio, jadi perlu kolom sendiri.
        if (! Schema::hasColumn('members', 'motto')) {
            Schema::table('members', function (Blueprint $table) {
                $table->string('motto')->nullable()->after('description');
            });
        }

        // Riwayat member (undo) harus menyimpan motto juga, kalau tidak restore kehilangan field ini.
        if (Schema::hasTable('member_histories') && ! Schema::hasColumn('member_histories', 'motto')) {
            Schema::table('member_histories', function (Blueprint $table) {
                $table->string('motto')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('members', 'motto')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('motto');
            });
        }

        if (Schema::hasTable('member_histories') && Schema::hasColumn('member_histories', 'motto')) {
            Schema::table('member_histories', function (Blueprint $table) {
                $table->dropColumn('motto');
            });
        }
    }
};
