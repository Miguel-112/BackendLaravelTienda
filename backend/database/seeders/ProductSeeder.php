<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('products')->insert([

            'name' => "neumatico",
            'descripcion' =>"neumatico numero 18"
        ]);

        DB::table('products')->insert([

            'name' => "aceite",
            'descripcion' =>"aceite motul"
        ]);

        DB::table('products')->insert([

            'name' => "bugia",
            'descripcion' =>"bugia para boxer"
        ]);
    }
}
