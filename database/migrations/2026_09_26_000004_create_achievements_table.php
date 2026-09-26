<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 20)->nullable();
            $table->string('title');
            $table->string('year', 20)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Pindahkan 8 slot lama dari landing_contents ke tabel, lalu buang key lamanya.
        if (Schema::hasTable('landing_contents')) {
            $legacyKeys = [];

            for ($index = 1; $index <= 8; $index++) {
                $keys = [
                    "achievement_{$index}_icon",
                    "achievement_{$index}_title",
                    "achievement_{$index}_year",
                    "achievement_{$index}_desc",
                ];

                $values = DB::table('landing_contents')->whereIn('key', $keys)->pluck('value', 'key');

                $title = $values["achievement_{$index}_title"] ?? null;
                if (blank($title)) {
                    continue;
                }

                DB::table('achievements')->insert([
                    'icon' => $values["achievement_{$index}_icon"] ?? null,
                    'title' => $title,
                    'year' => $values["achievement_{$index}_year"] ?? null,
                    'description' => $values["achievement_{$index}_desc"] ?? null,
                    'sort_order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $legacyKeys = array_merge($legacyKeys, $keys);
            }

            if ($legacyKeys !== []) {
                DB::table('landing_contents')->whereIn('key', $legacyKeys)->delete();
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
