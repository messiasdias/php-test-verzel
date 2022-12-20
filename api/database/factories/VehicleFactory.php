<?php

namespace Database\Factories;


class VehicleFactory extends FactoryBase
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(12),
            'description' => $this->faker->description(),
        ];
    }
}
