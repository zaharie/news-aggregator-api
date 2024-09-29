<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_can_get_articles_with_filters()
    {

        Article::factory()->create([
            'author' => 'John Doe',
            'category' => 'Technology'
        ]);

        Article::factory()->create([
            'author' => 'Jane Smith',
            'category' => 'Science'
        ]);

        $response = $this->getJson('/api/articles?author=John');
        $response->assertStatus(200);
        $response->assertJsonFragment(['author' => 'John Doe']);
        $response->assertJsonMissing(['author' => 'Jane Smith']);
    }

    public function test_can_get_article_by_id()
    {
        $article = Article::factory()->create([
            'title' => 'Test Article',
            'author' => 'John Doe'
        ]);
        $response = $this->getJson('/api/articles/' . $article->id);
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Test Article']);
    }


    public function test_returns_404_if_article_not_found()
    {
        $response = $this->getJson('/api/articles/999');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Article not found']);
    }
}
