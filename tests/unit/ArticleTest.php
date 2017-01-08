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
        
        $article2->created_at = $twoWeeksAgo;
        $article2->save();
        
        $articles = Article::filterBetweenDates($twoWeeksAgo, $now)->get();

        $this->assertTrue($articles->contains($article));
        $this->assertTrue(!$articles->contains($article2));
    }
}
