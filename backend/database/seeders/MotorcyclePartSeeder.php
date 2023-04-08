<?php

namespace Database\Seeders;

use App\Models\MotorcyclePart;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotorcyclePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MotorcyclePart::factory()->count(20)->make()->each(function ($part) {
            $part->save();
        });
    }
}
