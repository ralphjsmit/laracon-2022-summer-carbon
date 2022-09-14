<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => fake()->sentence(),
            'author_id' => fake()->randomElement(Author::pluck('id')),
            'published_at' => fake()->dateTimeBetween('-12 months', '+1 week'),
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
