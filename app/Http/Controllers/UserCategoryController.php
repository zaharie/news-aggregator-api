<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;

class UserCategoryController extends Controller
{
 
    const CATEGORIES = [
        "Politics", "Economy", "Technology", "Science and Health", 
        "Sports", "Entertainment and Culture", "Environment", 
        "Education", "Security and Justice", "International"
    ];


    public function addCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:' . implode(',', self::CATEGORIES),
        ]);

        $user = Auth::user();
        $favorites = $user->favorite_categories ?? [];

        if (!in_array($request->category, $favorites)) {
            $favorites[] = $request->category;
        }

        $user->favorite_categories = $favorites;
        $user->save();

        return response()->json(['message' => 'Category added to favorites', 'favorites' => $favorites]);
    }

    public function removeCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:' . implode(',', self::CATEGORIES),
        ]);

        $user = Auth::user();
        $favorites = $user->favorite_categories ?? [];

        if (($key = array_search($request->category, $favorites)) !== false) {
            unset($favorites[$key]);
        }

        $user->favorite_categories = array_values($favorites);
        $user->save();

        return response()->json(['message' => 'Category removed from favorites', 'favorites' => $favorites]);
    }


    public function favoriteArticles(Request $request)
    {
        $user = Auth::user();
        $categories = $user->favorite_categories ?? [];
    
        $author = $request->query('author');
        $page = $request->query('page', 1);
        $query = Article::whereIn('category', $categories);
        if ($author) {
            $query->where('author', 'like', '%' . $author . '%');
        }

        $articles = $query->paginate(50, ['*'], 'page', $page);
        return response()->json($articles);
    }
    
}
