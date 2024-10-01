<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 10), // Assuming you have 10 users
            'title' => fake()->jobTitle,
            'logo' => fake()->imageUrl(100, 100, 'business', true), // Generates a random image URL
            'tags' => implode(',', fake()->words(3)), // Creates a comma-separated string of tags
            'company' => fake()->company,
            'location' => fake()->city,
            'email' => fake()->companyEmail,
            'website' => fake()->domainName,
            'description' => fake()->paragraphs(3, true), // Generates a multi-paragraph description
            'created_at' => now(),
            'updated_at' => now(),
        ];
       
    }
}
