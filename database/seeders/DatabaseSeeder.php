<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sigma.id'],
            [
                'name' => 'Admin SIGMA',
                'password' => 'password',
            ]
        );

        $this->call(LandingContentSeeder::class);
    }
}
