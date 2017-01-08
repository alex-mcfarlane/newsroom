<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Article;
use App\Category;

class ArticleTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * @test
     * Test creating an article
     *
     */
    public function can_create_an_article()
    {
        $article = Article::create([
            'title' => 'My Article',
            'body' => 'Article body'
        ]);
        
        $this->assertEquals('My Article', $article->title);
        $this->assertEquals('Article body', $article->body);
    }
    
    /** @test */
    public function can_create_an_article_with_a_category()
    {
        $article = Article::create([
            'title' => 'My Article',
            'body' => 'Article body'
        ]);
        
        $category = Category::create([
            'title' => 'PHP',
            'description' => 'Articles related to PHP'
        ]);
        
        $article->category()->associate($category);
        
        $this->assertEquals('My Article', $article->title);
        $this->assertEquals('Article body', $article->body);
        $this->assertEquals($category, $article->category);
    }
    
    /** @test */
    public function can_filter_articles_by_date()
    {   
        $article = Article::create([
            'title' => 'My Article',
            'body' => 'Article body'
        ]);
        
        $articles = Article::filterByDay($article->created_at)->get();

        $this->assertTrue($articles->contains($article));
    }
    
    /** @test */
    public function can_filter_articles_between_dates()
    {
        $now = date('Y-m-d H:i:s', time());
        $twoWeeksAgo = date('Y-m-d H:i:s', time() - (60 * 60 * 24 * 14)); //two weeks ago
        $threeWeeksAgo = date('Y-m-d H:i:s', time() - (60 * 60 * 24 * 21)); //three weeks ago
        
        $article = Article::create([
            'title' => 'My Article',
            'body' => 'Article body'
        ]);

        $article2 = Article::create([
            'title' => 'My second article',
            'body' => 'This is my second article'
        ]);
        
        $article2->created_at = $threeWeeksAgo;
        $article2->save();
        
        $articles = Article::filterBetweenDates($twoWeeksAgo, $now)->get();

        $this->assertTrue($articles->contains($article));
        $this->assertTrue(!$articles->contains($article2));
    }

    /** @test */
    public function get_newest_article_for_each_category()
    {
        $category1 = Category::create([
            'title' => 'PHP',
            'description' => 'Articles related to PHP'
        ]);

        $category2 = Category::create([
            'title' => 'JavaScript',
            'description' => 'Articles related to JavaScript'
        ]);

        $category3 = Category::create([
            'title' => 'Algorithms',
            'description' => 'Trees, Sorting, Order, etc...'
        ]);

        $articlePHP = Article::create([
            'title' => 'My PHP Article',
            'body' => 'Article body'
        ]);
        $articlePHP->setCategory($category1->id);

        $articleJS = Article::create([
            'title' => 'My JS Article',
            'body' => 'Article body'
        ]);
        $articleJS->setCategory($category2->id);

        $articleJS2 = Article::create([
            'title' => 'My second JS Article',
            'body' => 'Article body'
        ]);
        $articleJS2->setCategory($category2->id);

        $articleAlgo = Article::create([
            'title' => 'My Algo Article',
            'body' => 'Article body'
        ]);
        $articleAlgo->setCategory($category3->id);

        $articleAlgo2 = Article::create([
            'title' => 'My Second Algo Article',
            'body' => 'Article body'
        ]);
        $articleAlgo2->setCategory($category3->id);

        $articles = Article::newestForEachCategory();

        $this->assertTrue($articles->contains($articlePHP));
        $this->assertTrue($articles->contains($articleJS2));
        $this->assertTrue($articles->contains($articleAlgo2));
    }
}
