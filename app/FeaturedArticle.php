<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Newsroom\Interfaces\IModelFormatter;

class FeaturedArticle extends Article
{
    protected $table = 'articles';
    protected $appends = [
        'order'
    ];
    
    public static function all($columns = ['*'], IModelFormatter $formatter = null)
    {
        $featuredArticles = FeaturedArticle::with(['category', 'image'])->featured()->select('articles.*')->get();

        if($formatter) {
            foreach($featuredArticles as $article) {
                $article = $formatter->format($article);
            }
        }

        return $featuredArticles;
    }
    
    public function getOrderAttribute()
    {
        $order = FeaturedArticle::featuredArticle($this->id)
                    ->select('featured_articles.order_id')->first();
        
        $order ? $orderId  = $order->order_id : $orderId = null;
        
        return $orderId;
    }
    
    public function setSortOrder($order, $originalOrder = 0)
    {
        $originalOrder == 0 ? $originalOrder = $this->order : '';
        
        if($this->hasSortOrder()) {
            $this->removeSortOrder();
        }

        if($article = Article::featured()->where('featured_articles.order_id', $order)->first()){
            $newOrder = $order > $originalOrder ? $order - 1 : $order + 1;
            $article->setSortOrder($newOrder, $originalOrder);
        }

        $this->addSortOrder($order);
    }

    private function addSortOrder($order)
    {
        DB::table('featured_articles')->insert(['article_id'=> $this->id, 'order_id' => $order]);
    }

    private function removeSortOrder()
    {
        DB::delete('delete from featured_articles where article_id = ?', [$this->id]);
    }

    private function hasSortOrder()
    {
        return !empty(FeaturedArticle::featuredArticle($this->id)->first());
    }
    
    public function scopeFeatured($query)
    {
        return $query->join('featured_articles', 'articles.id', '=', 'featured_articles.article_id')
                        ->orderBy('featured_articles.order_id');
    }

    public function scopeFeaturedArticle($query, $articleId)
    {
        return $query->featured()->where('featured_articles.article_id', $articleId);
    }
}
