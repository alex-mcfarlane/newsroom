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

    public function edit($title, $body, $isFeatured, $categoryId)
    {
        $this->fill(['title'=>$title, 'body' => $body]);
        
        $this->setCategory($categoryId);

        $this->setFeatured($isFeatured);

        $this->save();
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

        if($article) {
            $article->setImage();
        }

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
    
    public function setFeatured($featured)
    {
        if(isset($featured)) {
            if($featured === true) {
                $this->markAsFeatured();
            } else {
                $this->unfeature();
            }   
        }
    }
    
    public function setCategory($categoryId)
    {
        if(isset($categoryId)) {

            if($categoryId === 0) {
                $this->clearCategory();
                return;
            }

            //associate article with category
            if(! $category = Category::find($categoryId)) {
                throw new CategoryNotFoundException;
            }
            
            $this->category()->associate($category);
            
            $this->save();
        }
    }

    public function setImage()
    {
        if( !array_key_exists('image', $this->relations) || $this->relations['image'] == null) {
            $this->relations['image'] = Image::defaultImage();
        }
    }

    private function clearCategory()
    {
        $this->category()->dissociate();
        $this->save();
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
        $article = $query->first();
        
        if($article == null)
        {
            $article = new Article;
            $article->relations['image'] = null;
            return $article;
        }
        
        return $article;
    }
    
    public function scopeNewestArticles($query, $num)
    {
        return $query->limit($num)->get();
    }
}
