<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Newsroom\Exceptions\CategoryNotFoundException;
use App\Image;
use App\Newsroom\Images\LocalFileStore;

class Article extends Model
{
    protected $fillable = ["title", "body"];
    
    protected $attributes = [
        'headliner' => false
    ];
    
    public static function fromForm($title, $body, $isHeadliner = false, $categoryId = 0)
    {        
        $article = self::create([
            "title" => $title,
            "body" => $body,
            "headliner" => false
        ]);
        
        // By default, category is null and headliner is false. Only use setters if client explicitly passes them in
        $categoryId ? $article->setCategory($categoryId) : '';
        $isHeadliner ? $article->markAsHeadliner() : '';
        
        return $article;
    }

    public function edit($title, $body, $isHeadliner, $categoryId)
    {
        $this->fill(['title'=>$title, 'body' => $body]);

        $this->setHeadliner($isHeadliner);

        if(isset($categoryId)) {
            $this->setCategory($categoryId);
        }

        $this->save();
    }
    
    public function delete()
    {
        if($this->hasImage()) {
            $this->image->remove(new LocalFileStore);
        }

        parent::delete();
    }

    public static function withSubResources($id)
    {
        $article = self::with(['category', 'image'])->findOrFail($id);
        
        $article->setImage();

        return $article;
    }
    
    public static function headliner()
    {
        $article = self::where('headliner', true)->with('image')->first();

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
        return $this->hasOne('App\Image', 'article_id');
    }
    
    public function setHeadliner($headliner)
    {
        if($headliner == true) {
            if($this->headliner == false) {
                $this->markAsHeadliner();
            }
        } else {
            $this->removeAsHeadliner();
        }
    }
    
    public function setCategory($categoryId)
    {
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

    public function setImage()
    {
        if( !array_key_exists('image', $this->relations) || $this->relations['image'] == null) {
            $this->relations['image'] = Image::defaultImage();
        }
    }

    public function addImage(Image $image)
    {
        $this->image()->save($image);
    }

    public function changeImage(Image $image)
    {
        $this->image()->delete();

        $this->addImage($image);
    }

    public function hasImage()
    {
        return is_null($this->image) === false;
    }

    private function clearCategory()
    {
        $this->category()->dissociate();
        $this->save();
    }
    
    public function markAsHeadliner()
    {
        //if another article(s) is the headliner, we need to unfeature them
        if($article = Article::headliner()) {
            $article->removeAsHeadliner();
        }
        
        $this->headliner = true;
        $this->save();
    }
    
    private function removeAsHeadliner()
    {
        $this->headliner = false;
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
