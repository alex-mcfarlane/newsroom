<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ["title", "description"];
    
    public function articles()
    {
        return $this->hasMany('App\Article');
    }
    
    public function getNewestArticle()
    {
        return $this->getNewestArticles(1)->first();
    }
    
    public function getNewestArticles($num)
    {
        return $this->articles()->orderBy('created_at', 'desc')->limit($num)->get();
    }
}
