<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('incidents')->insert([
            [
                'title' => 'Street Fight',
                'description' => 'A small street fight in downtown Dhaka',
                'latitude' => 23.8103,
                'longitude' => 90.4125,
                'severity' => 3,
                'category' => 'Violence',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'occurred_at' => '2026-02-20 14:30:00',
            ],
            [
                'title' => 'Bag Snatching',
                'description' => 'Snatching incident at Chattogram market',
                'latitude' => 22.3569,
                'longitude' => 91.7832,
                'severity' => 2,
                'category' => 'Theft',
                'division' => 'Chattogram',
                'district' => 'Chattogram',
                'occurred_at' => '2026-02-18 10:15:00',
            ],
            [
                'title' => 'Minor Road Accident',
                'description' => 'Two cars collided at a junction in Sylhet',
                'latitude' => 24.8949,
                'longitude' => 91.8687,
                'severity' => 1,
                'category' => 'Accident',
                'division' => 'Sylhet',
                'district' => 'Sylhet',
                'occurred_at' => '2026-02-19 09:00:00',
            ],
            [
                'title' => 'Protest Rally',
                'description' => 'Protest in Rajshahi city center',
                'latitude' => 24.3745,
                'longitude' => 88.6042,
                'severity' => 4,
                'category' => 'Protest',
                'division' => 'Rajshahi',
                'district' => 'Rajshahi',
                'occurred_at' => '2026-02-21 16:00:00',
            ],
            [
                'title' => 'Shop Burglary',
                'description' => 'Burglary in a local store',
                'latitude' => 23.4659,
                'longitude' => 89.7351,
                'severity' => 3,
                'category' => 'Theft',
                'division' => 'Khulna',
                'district' => 'Khulna',
                'occurred_at' => '2026-02-17 22:45:00',
            ],
            // Add more as needed for testing
        ]);
    }
}
