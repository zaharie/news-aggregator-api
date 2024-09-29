<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $author = $request->query('author');
        $category = $request->query('category');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');     
        $page = $request->query('page', 1);
    
        $query = Article::query();

        if ($author) {
            $query->where('author', 'ilike', '%' . $author . '%');
        }
    
        if ($category) {
            $query->where('category', 'ilike', '%' . $category . '%');
        }
    
        if ($startDate && $endDate) {
            $query->whereBetween('published_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('published_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('published_at', '<=', $endDate);
        }
    
        $articles = $query->paginate(50, ['*'], 'page', $page);
    
        return response()->json($articles);
    }


    public function getOneById($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        return response()->json($article);
    }
}
