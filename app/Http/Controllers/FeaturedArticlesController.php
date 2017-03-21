<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\FeaturedArticle;
use App\Newsroom\Articles\ArticleFormatter;

class FeaturedArticlesController extends Controller
{
    protected $articleFeaturer;
    
    public function __construct()
    {
        //$this->middleware('jwt.auth', ['except' => ['index']]);
    }
    
    public function index()
    {
        return response()->json(FeaturedArticle::all('*', new ArticleFormatter));
    }
    
    public function store(Request $request, $articleId)
    {
        $article = FeaturedArticle::find($articleId);
        $article->setSortOrder($request->input('order_id'));

        return response()->json('Article has been featured', 200);
    }

    public function destroy($articleId)
    {
        $article = FeaturedArticle::find($articleId);
        $article->removeFromFeaturedArticles();

        return response()->json('Article is no longer a feature article', 200);
    }
}