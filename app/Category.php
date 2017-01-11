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
    
    public function newestArticlesQuery()
    {
        return $this->articles()->orderBy('created_at', 'desc');
    }
}
