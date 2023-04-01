<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Frenos', 'description' => 'Categoría de repuestos para frenos'],
            ['name' => 'Embragues', 'description' => 'Categoría de repuestos para embragues'],
            ['name' => 'Cables', 'description' => 'Categoría de repuestos para cables'],
            ['name' => 'Llantas', 'description' => 'Categoría de repuestos para llantas'],
            ['name' => 'Baterías', 'description' => 'Categoría de repuestos para baterías'],
            ['name' => 'Aceites y lubricantes', 'description' => 'Categoría de aceites y lubricantes para motores'],
            ['name' => 'Filtros', 'description' => 'Categoría de repuestos para filtros'],
            ['name' => 'Luces', 'description' => 'Categoría de repuestos para luces'],
        ];

        DB::table('categories')->insert($categories);
    }
}
