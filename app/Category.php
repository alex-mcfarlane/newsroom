<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    protected $fillable = ["title", "description"];
    
    public function articles()
    {
        return $this->hasMany('App\Article');
    }
    
    public function newestArticlesQuery()
    {
        return $this->articles()->with('image')->orderBy('created_at', 'desc');
    }
    
    public static function popular($limit = null)
    {
        $builder = Category::select(DB::raw('categories.*, count(*) as article_count'))
            ->join('articles', 'categories.id', '=', 'articles.category_id')
            ->groupBy('category_id')
            ->orderBy('article_count', 'desc');
        
        if($limit) {
            $builder->limit($limit)->get();
        }
        
        return $builder->get();
    }
}
