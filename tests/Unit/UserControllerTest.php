<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
class UserControllerTest  extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

   
        $this->user = User::factory()->create();


        Auth::login($this->user);
    }

    public function test_can_add_category_to_favorites()
    {
        $response = $this->postJson('/api/user/categories/add', [
            'category' => 'Technology'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Category added to favorites']);
        $this->assertContains('Technology', $this->user->fresh()->favorite_categories);
    }

    public function test_cannot_add_invalid_category()
    {
        $response = $this->postJson('/api/user/categories/add', [
            'category' => 'InvalidCategory'
        ]);

        $response->assertStatus(422);
        $this->assertEmpty($this->user->fresh()->favorite_categories);
    }

    public function test_can_remove_category_from_favorites()
    {
        $this->user->favorite_categories = ['Technology', 'Sports'];
        $this->user->save();

        $response = $this->postJson('/api/user/categories/remove', [
            'category' => 'Technology'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Category removed from favorites']);
        $this->assertNotContains('Technology', $this->user->fresh()->favorite_categories);
    }

    public function test_cannot_remove_category_not_in_favorites()
    {
    
        $response = $this->postJson('/api/user/categories/remove', [
            'category' => 'Economy'
        ]);

        $response->assertStatus(200); 
        $response->assertJsonFragment(['message' => 'Category removed from favorites']);
        $this->assertEmpty($this->user->fresh()->favorite_categories);
    }

    public function test_can_get_favorite_articles()
    {
        
        $this->user->favorite_categories = ['Technology'];
        $this->user->save();

 
        Article::factory()->create([
            'author' => 'John Doe',
            'category' => 'Technology'
        ]);

        Article::factory()->create([
            'author' => 'Jane Smith',
            'category' => 'Sports'
        ]);

     
        $response = $this->getJson('/api/user/favorite-articles');

        $response->assertStatus(200);
        $response->assertJsonFragment(['author' => 'John Doe']);
        $response->assertJsonMissing(['author' => 'Jane Smith']);
    }

    public function test_can_filter_favorite_articles_by_author()
    {

        $this->user->favorite_categories = ['Technology'];
        $this->user->save();

 
        Article::factory()->create([
            'author' => 'John Doe',
            'category' => 'Technology'
        ]);

        Article::factory()->create([
            'author' => 'Jane Smith',
            'category' => 'Technology'
        ]);

    
        $response = $this->getJson('/api/user/favorite-articles?author=John');

        $response->assertStatus(200);
        $response->assertJsonFragment(['author' => 'John Doe']);
        $response->assertJsonMissing(['author' => 'Jane Smith']);
    }
}
