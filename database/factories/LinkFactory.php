<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        dd($this->faker->slug(), $this->faker->url());
        return [
            'slug' => $this->faker->slug(),
            'target_url' => $this->faker->url(),
            'is_active' => true,
        ];
    }
}
