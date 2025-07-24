<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // public function definition(): array
    // {
    //     return [
    //         'title' => $this->faker->sentence,
    //         'content' => $this->faker->paragraph,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ];
    // }

    public function definition(): array
    {
        $cities = ['Mumbai', 'Delhi', 'Bengaluru', 'Kolkata', 'Chennai', 'Hyderabad', 'Jaipur', 'Goa', 'Pune', 'Ahmedabad'];
        $themes = [
            'Exploring the Streets of',
            'The Flavours of Indian Cuisine in',
            'Festivals of India:',
            'Startup Boom in',
            'Heritage Sites of',
            'Hidden Gems of',
            'Nightlife in',
            'Traditional Markets of'
        ];

        $city = $this->faker->randomElement($cities); // Removed unique()
        $theme = $this->faker->randomElement($themes);

        $title = "$theme $city";
        $content = "{$city} is known for " . $this->faker->sentence() . ' ' . $this->faker->paragraph();

        return [
            'title' => $title,
            'content' => $content,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
