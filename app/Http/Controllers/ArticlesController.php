<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Category;
use App\Newsroom\Articles\ArticleRetrieverService;
use App\Newsroom\Articles\ArticleUpdater;
use App\Newsroom\Exceptions\ArticleException;

class ArticlesController extends Controller
{
    protected $articleRetrieverService;
    protected $articleUpdater;

    public function __construct(ArticleRetrieverService $articleRetrieverService, ArticleUpdater $articleUpdater)
    {
        $this->articleRetrieverService = $articleRetrieverService;
        $this->articleUpdater = $articleUpdater;
    }
    
    public function show($id)
    {
        $article = Article::withSubResources($id);
        $categories = Category::all();
        $newestArticles = $this->articleRetrieverService->retrieveArticlesForCategories($categories, 1);
        
        return view('articles.view', compact('article', 'categories', 'newestArticles'));
    }

    public function update(Request $request, $articleId)
    {
        try{
            $attrs = $request->only('title', 'body', 'sub_title', 'headliner', 'category_id');
            $article = $this->articleUpdater->update($articleId, $attrs, $request->file('image'));
        } catch(ArticleException $e) {
            return response()->json(["errors" => $e->getErrors()], 400);
        }

        return back();
    }

    public function delete($id)
    {
        $article = Article::find($id);

        $article->delete();

        return redirect()->route('home');
    }
}
