<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BusSeeder::class,
            RouteSeeder::class,
            EtapeSeeder::class,
            ProgrammeSeeder::class,
            SegmentSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}