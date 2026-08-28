<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'type' => 'seminar',
            'description' => fake()->paragraph(),
            'start_time' => now()->addDays(7),
            'end_time' => now()->addDays(7)->addHours(2),
            'location' => fake()->city(),
            'poster' => null,
        ];
    }
}
