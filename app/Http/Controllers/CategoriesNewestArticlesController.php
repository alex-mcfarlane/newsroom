<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Newsroom\Articles\ArticleRetrieverService;

class CategoriesNewestArticlesController extends Controller
{
    protected $articleRetrieverService;
    
    public function __construct(ArticleRetrieverService $retrieverService)
    {
        $this->articleRetrieverService = $retrieverService;
    }
    
    public function index(Request $request)
    {
        $articles = $this->articleRetrieverService->retrieveArticlesForCategories($request->input('limit'));
        
    	return response()->json($articles);
    }
}