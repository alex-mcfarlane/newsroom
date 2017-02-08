<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Category;
use App\Newsroom\Articles\ArticleRetrieverService;

class ArticlesController extends Controller
{
    protected $articleRetrieverService;
    
    public function __construct(ArticleRetrieverService $articleRetrieverService)
    {
        $this->articleRetrieverService = $articleRetrieverService;
    }
    
    public function show($id)
    {
        $article = Article::withSubResources($id);
        $newestArticles = $this->articleRetrieverService->retrieveArticlesForCategories(Category::all(), 1);
        
        return view('articles.view', compact('article', 'newestArticles'));
    }
}
