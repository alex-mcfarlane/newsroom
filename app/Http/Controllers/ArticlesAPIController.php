<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Newsroom\Articles\ArticleCreator;
use App\Newsroom\Articles\ArticleUpdater;
use App\Newsroom\Articles\ArticleQuerier;
use App\Newsroom\Articles\ArticleHeadliner;
use App\Newsroom\Articles\ArticleFormatter;
use App\Newsroom\Images\ImageCreator;
use App\Newsroom\Exceptions\ArticleException;
use App\Newsroom\Exceptions\ImageException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticlesAPIController extends Controller
{
    protected $articleCreator;
    protected $articleUpdater;
    protected $imageCreator;

    public function __construct(ArticleCreator $articleCreator, ArticleUpdater $articleUpdater,
        ImageCreator $imageCreator, ArticleHeadliner $articleHeadliner)
    {
        $this->middleware('jwt.auth', ['except' => ['index']]);
        $this->articleCreator = $articleCreator;
        $this->articleUpdater = $articleUpdater;
        $this->imageCreator = $imageCreator;
        $this->articleHeadliner = $articleHeadliner;
    }
    
    public function index(Request $request)
    {
        $articleQuerier = new ArticleQuerier($request->all());
        $articles = $articleQuerier->search(new ArticleFormatter);

        return response()->json($articles);
    }

    public function store(Request $request)
    {        
        try{
            $article = $this->articleCreator->make($this->getInput($request));
        }
        catch(ArticleException $e) {
            return response()->json($e->getErrors());
        }
        
        return response()->json($article);
    }

    public function update(Request $request, $articleId)
    {
        try{
            $article = $this->articleUpdater->update($articleId, $this->getInput($request));
        } catch(ArticleException $e) {
            return response()->json(["errors" => $e->getErrors()], 400);
        }

        return response()->json($article, 200);
    }
    
    public function show($id)
    {
        try{
            return response()->json(Article::withSubResources($id));
        } catch(ModelNotFoundException $e) {
            return response()->json($e->getMessage());
        }
    }

    public function addImage(Request $request, $articleId)
    {
        if(!$article = Article::find($articleId)) {
            return new ImageException(['Article not found'], 404);
        }

        try{
            $image = $this->imageCreator->make($request->file('image'));
            
            $article->addImage($image);
            
            return response()->json($image);
        } catch(ImageException $e) {
            return response()->json($e->getErrors());
        }
    }
    
    public function makeHeadliner($id)
    {   
        try{
            $article = $this->articleHeadliner->headline($id, true);
            return response()->json($article, 200);
        } catch (\App\Newsroom\Exceptions\ArticleException $ex) {
            return response()->json($ex->getErrors(), $ex->getHttpStatusCode());
        }
    }
    
    public function removeHeadliner($id)
    {
        try{
            $this->articleHeadliner->headline($id, false);
            return response()->json(null, 204);
        } catch (\App\Newsroom\Exceptions\ArticleException $ex) {
            return response()->json($ex->getErrors(), $ex->getHttpStatusCode());
        }
    }

    private function getInput(Request $request)
    {
        return $request->only('title', 'body', 'headliner', 'category_id');
    }
}
