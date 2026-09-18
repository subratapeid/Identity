<?php

namespace Identity\Database\Seeders;

use Illuminate\Database\Seeder;

class IdentityDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,


        ]);
    }
}