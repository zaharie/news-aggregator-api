<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $author = $request->query('author');
        $theme = $request->query('theme');
        $page = $request->query('page', 1);
        $query = Article::query();

        if ($author) {
            $query->where('author', 'like', '%' . $author . '%');
        }

        if ($theme) {
            $query->where('theme', 'like', '%' . $theme . '%');
        }

        $articles = $query->paginate(50, ['*'], 'page', $page);
        return response()->json($articles);
    }
}
