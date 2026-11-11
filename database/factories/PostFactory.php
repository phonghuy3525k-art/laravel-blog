<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            'user_id' => \App\Models\User::factory(), // Tự động tạo user mới nếu chưa có
            'category_id' => \App\Models\Category::factory(), // Tự động tạo category mới nếu chưa có
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'content' => $this->faker->paragraphs(3, true),
            'thumbnail' => 'https://via.placeholder.com/600x400',
            'status' => $this->faker->randomElement(['draft', 'published', 'scheduled']),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}