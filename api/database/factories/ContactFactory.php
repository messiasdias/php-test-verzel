<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends FactoryBase
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'phone' => trim(str_replace(["(", ")", "-", " "],"", $this->faker->unique()->phoneNumber)),
            'state' => "PR",
            'city' =>  $this->faker->city,
            'subject' => "Assunto Fake",
            'message' =>  $this->faker->sentence(),
            'status'  => "new",
            'readed_by'  => null,
            'readed_at'  => null,
            'resolved_by'  => null,
            'resolved_at'  => null,
            'email_confirmed_at' => date("Y-m-d H:i:s"),
            'phone_confirmed_at' =>  date("Y-m-d H:i:s"),
        ];
    }

    /**
     * Indicate that the model status is equals to new.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function isNew()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'new',
                'readed_at' => null,
                'readed_by' => null,
                'resolved_at' => null,
                'resolved_by' => null,
                ...$attributes
            ];
        });
    }

    /**
     * Indicate that the model status is equals to Readed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function isReaded()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'readed',
                'readed_at' => now(),
                'readed_by' => 1,
                ...$attributes
            ];
        });
    }

    /**
     * Indicate that the model status is equals to Resolved.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function isResolved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'resolved',
                'resolved_at' => now(),
                'resolved_by' => 1,
                ...$attributes
            ];
        });
    }
}
