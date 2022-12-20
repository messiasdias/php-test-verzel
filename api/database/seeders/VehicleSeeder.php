<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 50) as $id) {
                $idText = $id > 0 ? $id : "";
                $vehicle = Vehicle::create([
                    "name" => "Veículo $idText",
                    "description" => "Texto descritivo sobre o veículo $idText",
                    "price" => rand(12500.55, 100289.98),
                    "active" => true
                ]);

                $vehicle->save();
        }
    }
}
