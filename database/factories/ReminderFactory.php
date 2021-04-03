<?php

namespace Database\Factories;

use App\Models\Reminder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reminder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'psid' => 'PS_ID',
            'first_name' => 'TEST_FIRST_NAME',
            'action' => 'ACTION',
            'remind_at' => '2021-04-03 10:00:00',
        ];
    }
}
