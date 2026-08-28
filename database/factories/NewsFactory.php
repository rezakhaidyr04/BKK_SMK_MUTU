<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'slug' => fake()->unique()->slug(),
            'category' => fake()->randomElement(['Tips Karir', 'Lowongan', 'Pengumuman']),
            'content' => fake()->paragraphs(3, true),
            'thumbnail' => null,
            'is_published' => true,
        ];
    }
}
