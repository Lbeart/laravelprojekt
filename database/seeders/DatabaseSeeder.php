<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Thirr seedera për user-at (admin dhe user)
        $this->call(UserSeeder::class);
    }
}
