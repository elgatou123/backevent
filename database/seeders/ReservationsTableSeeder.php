<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationsTableSeeder extends Seeder
{
    public function run(): void
    {
       DB::table('reservations')->insert([
            [
                'preferred_date' => Carbon::now()->addDays(10)->toDateString(),
                'preferred_time' => '14:00:00',
                'special_note' => 'Please arrange for a projector.',
                'user_id' => 1, // Assuming user with ID 1 exists
                'event_id' => 1, // Assuming event with ID 1 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'preferred_date' => Carbon::now()->addDays(15)->toDateString(),
                'preferred_time' => '18:00:00',
                'special_note' => null,
                'user_id' => 1, // Assuming user with ID 2 exists
                'event_id' => 2, // Assuming event with ID 2 exists
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
