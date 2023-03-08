<?php

namespace Database\Seeders;

use App\Models\Transport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transport::truncate();
        Transport::create([
            'name' => 'Piedi'
        ]);
        Transport::create([
            'name' => 'Bicicletta'
        ]);
        Transport::create([
            'name' => 'Bus comunale'
        ]);
        Transport::create([
            'name' => 'Auto'
        ]);
        Transport::create([
            'name' => 'Auto collettiva (2 Studenti)'
        ]);
        Transport::create([
            'name' => 'Auto collettiva (3+ Studenti)'
        ]);
        Transport::create([
            'name' => 'Treno'
        ]);
    }
}
