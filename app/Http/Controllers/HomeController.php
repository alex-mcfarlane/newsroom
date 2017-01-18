<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
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
        $featuredArticle = Article::featured();        
        $newestArticles = $this->articleRetrieverService->retrieveArticlesForCategories();

        return view('home', compact('featuredArticle', 'newestArticles'));
    }
}