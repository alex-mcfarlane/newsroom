<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Newsroom\Exceptions\CategoryNotFoundException;
use App\Image;

class Article extends Model
{
    protected $fillable = ["title", "body"];
    
    protected $attributes = [
        'featured' => false
    ];
    
    public static function fromForm($title, $body, $isFeatured)
    {        
        $article = self::create([
            "title" => $title,
            "body" => $body,
            "featured" => false
        ]);
        
        if($isFeatured) {
            $article->markAsFeatured();
        }
        
        return $article;
    }
    
    public static function createCategorizedArticle($title, $body, $isFeatured, $categoryId)
    {
        $article = Article::fromForm($title, $body, $isFeatured);
        
        $article->setCategory($categoryId);
        
        return $article;
    }
    
    public static function withSubResources($id)
    {
        $article = self::with(['category', 'image'])->findOrFail($id);
        
        $article->setImage();

        return $article;
    }
    
    public static function featured()
    {
        $article = self::where('featured', true)->with('image')->first();

        $article->setImage();

        return $article;
    }
    
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function image()
    {
        return $this->hasOne('App\Image');
    }
    
    public function getImagePath()
    {
        if(is_null($this->relations['image'])) {
            $image = Image::defaultImage();
            return $image->path;
        }
        return $this->image()->path;
    }
    
    public function setFeatured($featured)
    {
        if($featured === true) {
            $this->markAsFeatured();
        } else {
            $this->unfeature();
        }
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

    public function setImage()
    {
        if($this->relations['image'] == null) {
            $this->relations['image'] = Image::defaultImage();
        }
    }

    public function addImage(Image $image)
    {
        $this->image()->save($image);
    }
    
    public function markAsFeatured()
    {
        //if another article(s) is featured, we need to unfeature them
        foreach(Article::where('featured', true)->get() as $article) {
            $article->unfeature();
        }
        
        $this->featured = true;
        $this->save();
    }
    
    private function unfeature()
    {
        $this->featured = false;
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
