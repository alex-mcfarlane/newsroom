<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Category;
use App\Newsroom\Articles\ArticleRetrieverService;

class HomeController extends Controller
{
    protected $articleRetrieverService;
    
    public function __construct(ArticleRetrieverService $retrieverService)
    {
        $this->articleRetrieverService = $retrieverService;
    }
    
    public function index()
    {
        $headlineArticle = Article::headliner();        
        $newestArticles = $this->articleRetrieverService->retrieveArticlesForCategories(Category::all(), 1);

        return view('home', compact('headlineArticle', 'newestArticles'));
    }
}