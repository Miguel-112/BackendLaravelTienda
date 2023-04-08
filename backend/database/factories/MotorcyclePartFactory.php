<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Facades\Storage;

use App\Models\MotorcyclePart;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MotorcyclePart>
 */
class MotorcyclePartFactory extends Factory
{
    protected $model = MotorcyclePart::class;

    public function definition()
    {
        $image = $this->faker->image(storage_path('app/public/images'), 640, 480, null, false);

        return [
            'nombre' => $this->faker->word(),
            'categoria_id' => 1,
            'proveedor_id' => 1,
            'marca_id' => 1,
            'precio_compra' => $this->faker->randomFloat(2, 10, 1000),
            'precio_venta' => $this->faker->randomFloat(2, 10, 1000),
            'cantidad' => $this->faker->numberBetween(1, 100),
            'imagen' => Storage::putFile('public/images', $image),
            'iva' => $this->faker->numberBetween(0, 16),
        ];
    }
}
