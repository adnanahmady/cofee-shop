<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Development\DatabaseSeeder as DevelopmentSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Seed the application's database. */
    public function run(): void
    {
        if (app()->environment(['development', 'local'])) {
            $this->call(DevelopmentSeeder::class);
        }
        $this->call(DeliveryTypeSeeder::class);
    }
}
