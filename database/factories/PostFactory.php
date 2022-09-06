<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => fake()->sentence(),
            'published_at' => fake()->dateTimeBetween('-6 months', '+4 months'),
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
