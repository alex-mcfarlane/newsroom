<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Newsroom\Exceptions\CategoryNotFoundException;

class Article extends Model
{
    protected $fillable = ["title", "body"];
    
    protected $attributes = [
        'featured' => false
    ];
    
    public static function fromForm(array $attributes)
    {        
        $article = self::create([
            "title" => $attributes["title"],
            "body" => $attributes["body"],
        ]);
        
        $article->setFeatured($attributes['featured']);
        
        if(isset($attributes["category_id"])) {
            $article->setCategory($attributes["category_id"]);
        }
        
        return $article;
    }
    
    public static function withSubResources($id)
    {
        return self::with('category')->findOrFail($id);
    }
    
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
    public function setFeatured($featured)
    {
        if($featured === true) {
            $this->markAsFeatured();
        } else {
            $this->unfeature();
        }
    }
    
    private function markAsFeatured()
    {
        //if another article(s) is featured, we need to unfeature them
        foreach(Article::where('featured', true)->get() as $article) {
            $article->unfeature();
        }
        
        $this->featured = true;
        $this->save();
    }
    
    public function unfeature()
    {
        $this->featured = false;
        $this->save();
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
    
    /**
     * return articles created on a given day
     * @param DateTime $date should be in format Y:m:d H:i:s
     */
    public static function filterByDay($date)
    {
        return self::where('created_at', '>=', $date);
    }

    public static function scopeFilterByEndDate($query, $date)
    {
        return $query->where('created_at', '<', $date);
    }

    public static function scopeFilterByStartDate($query, $date)
    {
        return $query->where('created_at', '>', $date);
    }

    public static function scopeFilterBetweenDates($query, $start, $end)
    {
        return $query->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
    }
    
    public function scopeNewestArticle($query)
    {
        return $query->first();
    }
    
    public function scopeNewestArticles($query, $num)
    {
        return $query->limit($num)->get();
    }
}
