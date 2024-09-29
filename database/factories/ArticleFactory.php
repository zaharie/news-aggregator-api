<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'external_id' => md5(uniqid()),
            'category' => $this->faker->randomElement([
                'Politics', 'Economy', 'Technology', 'Science and Health',
                'Sports', 'Entertainment and Culture', 'Environment',
                'Education', 'Security and Justice', 'International'
            ]),
            'content' => $this->faker->paragraph,
            'url' => $this->faker->url,
            'published_at' => now(),
        ];
    }
}
