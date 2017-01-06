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
}
