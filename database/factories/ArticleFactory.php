<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->name,
            'url' => fake()->unique()->url,
            'text' => fake()->text,
            'size_kb' => fake()->randomFloat(2, 0, 999999),
            'word_count' => fake()->randomNumber(),
        ];
    }
}
