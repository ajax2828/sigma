<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('initial', 20)->nullable();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('member_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->string('action', 20);
            $table->string('initial', 20)->nullable();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'created_at']);
        });

        if (Schema::hasTable('landing_contents')) {
            for ($index = 1; $index <= 8; $index++) {
                $values = DB::table('landing_contents')
                    ->whereIn('key', [
                        "member_initial_{$index}",
                        "member_name_{$index}",
                        "member_role_{$index}",
                        "member_code_{$index}",
                        "member_desc_{$index}",
                        "member_photo_{$index}",
                    ])
                    ->pluck('value', 'key');

                $name = $values["member_name_{$index}"] ?? null;
                if (blank($name)) {
                    continue;
                }

                $now = now();
                $memberId = DB::table('members')->insertGetId([
                    'initial' => $values["member_initial_{$index}"] ?? null,
                    'name' => $name,
                    'role' => $values["member_role_{$index}"] ?? null,
                    'code' => $values["member_code_{$index}"] ?? null,
                    'description' => $values["member_desc_{$index}"] ?? null,
                    'photo' => $values["member_photo_{$index}"] ?? null,
                    'sort_order' => $index,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('member_histories')->insert([
                    'member_id' => $memberId,
                    'action' => 'migrated',
                    'initial' => $values["member_initial_{$index}"] ?? null,
                    'name' => $name,
                    'role' => $values["member_role_{$index}"] ?? null,
                    'code' => $values["member_code_{$index}"] ?? null,
                    'description' => $values["member_desc_{$index}"] ?? null,
                    'photo' => $values["member_photo_{$index}"] ?? null,
                    'created_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('member_histories');
        Schema::dropIfExists('members');
    }
};
