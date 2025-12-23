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
        $server = GmodServers::registerServer('Soku Windows LAN', '193.243.190.126', 27044);
        //GmodServers::factory(5)->create();
    }
}
