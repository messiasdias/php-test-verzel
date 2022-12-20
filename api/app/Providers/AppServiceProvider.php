<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Faker\Factory as Faker;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Faker\Generator::class, function () {
            $faker = \Faker\Factory::create('pt_BR');
            $faker->addProvider(new \Faker\Provider\pt_BR\Person($faker));
            $faker->addProvider(new \Faker\Provider\pt_BR\Address($faker));
            $faker->addProvider(new \Faker\Provider\pt_BR\PhoneNumber($faker));
            $faker->addProvider(new \Faker\Provider\pt_BR\Company($faker));
            $faker->addProvider(new \Faker\Provider\Lorem($faker));
            $faker->addProvider(new \Faker\Provider\Internet($faker));
            return $faker;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
