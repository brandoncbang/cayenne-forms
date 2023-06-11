<?php

namespace Database\Factories;

use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'form_id' => Form::factory(),
            'uuid' => fake()->uuid,
            'ip_address' => fake()->boolean ? fake()->ipv4 : fake()->ipv6,
            'user_agent' => fake()->userAgent,
            'data' => [
                'name' => fake()->name,
                'email' => fake()->safeEmail,
                'subject' => fake()->sentence,
                'message' => fake()->paragraph,
            ],
        ];
    }

    public function archived(): EntryFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'archived_at' => now(),
            ];
        });
    }
}
