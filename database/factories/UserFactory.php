<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'psid' => 'PS_ID',
            'access_token' => 'ACCESS_TOKEN',
        ];
    }

    /**
     * Add Accurate session and host to the user.
     */
    public function withSession(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'database_id' => 'DATABASE_ID',
                'session' => 'SESSION',
                'host' => 'HOST',
            ];
        });
    }

    public function withFbName(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'fb_firstname' => 'TEST_FIRST_NAME',
                'fb_lastname' => 'TEST_LAST_NAME',
            ];
        });
    }
}
