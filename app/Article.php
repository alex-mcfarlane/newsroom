<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Newsroom\Exceptions\CategoryNotFoundException;

class Article extends Model
{
    protected $fillable = ["title", "body"];
    
    public static function withSubResources($id)
    {
        return self::with('category')->findOrFail($id);
    }
    
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
    public function setCategory($categoryId)
    {
        //associate article with category
        if(! $category = Category::find($categoryId)) {
            throw new CategoryNotFoundException;
        }
        
        $this->category()->associate($category);
        
        $this->save();
    }
}
