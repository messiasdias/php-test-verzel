<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

abstract class FactoryBase extends Factory 
{
    public function __construct(...$args)
    {   
       parent::__construct(...$args);
       $this->faker = $this->withFaker();
    }

     /**
     * Define the model's default state.
     *
     * @return array
     */
    public abstract function definition();

     
    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    public function withFaker()  : \Faker\Generator
    {
        $faker = \Faker\Factory::create('pt_BR');
        $faker->addProvider(new \Faker\Provider\pt_BR\Person($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\Address($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\PhoneNumber($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\Company($faker));
        $faker->addProvider(new \Faker\Provider\Lorem($faker));
        $faker->addProvider(new \Faker\Provider\Internet($faker));
        return $faker;
    }
}