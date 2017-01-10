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

    public static function newestForEachCategory($categories)
    {
        $newestArticlesPerCategory = [];

        foreach($categories as $category)
        {
            $article = Article::where('category_id', $category->id)->orderBy('created_at', 'desc')
                        ->first();

            if($article)
            {
                $newestArticlesPerCategory[$category->title] = $article;
            }
        }

        return $newestArticlesPerCategory;
    }
}
