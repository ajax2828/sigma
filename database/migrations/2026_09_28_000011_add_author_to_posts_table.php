<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Penulis tidak lagi dikunci ke user yang membuat post. Semua post
            // lama dibuat oleh satu akun, sehingga kartu berita selalu
            // menampilkan nama yang sama.
            $table->string('author')->nullable()->after('title');
        });

        // Isi dengan nama pembuatnya, supaya post lama tidak kehilangan
        // informasi penulis saat kolom ini dipakai.
        DB::table('posts')->whereNull('author')->update([
            'author' => DB::raw('(SELECT name FROM users WHERE users.id = posts.user_id)'),
        ]);
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('author');
        });
    }
};
