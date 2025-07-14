<?php

namespace Database\Seeders;

use App\Models\GmodServers;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $server = GmodServers::registerServer('Soku Windows LAN', '192.168.30.1:27016');
        //GmodServers::factory(5)->create();
    }
}
