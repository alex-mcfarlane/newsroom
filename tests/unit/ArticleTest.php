<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Newsroom\Categories\RecentArticle;
use App\Newsroom\Categories\ArticleRetrieverOutput;
use App\Article;
use App\FeaturedArticle;
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
        $article = Article::fromForm('My Article', 'Article body');
        
        $this->assertEquals('My Article', $article->title);
        $this->assertEquals('Article body', $article->body);
    }
    
    /** @test */
    public function can_create_an_article_with_a_category()
    {
        $article = Article::fromForm('My Article', 'Article body');
        
        $category = Category::create([
            'title' => 'PHP',
            'description' => 'Articles related to PHP'
        ]);
        
        $article->associateCategory($category);
        
        $this->assertEquals('My Article', $article->title);
        $this->assertEquals('Article body', $article->body);
        $this->assertEquals($category, $article->category);
    }

    /** @test */
    public function can_edit_an_article()
    {
        $article = Article::fromForm('My Article', 'Article body');

        $category = Category::create([
            'title' => 'PHP',
            'description' => 'Articles related to PHP'
        ]);
        
        $article->associateCategory($category);

        $article->edit('My Article Edit', 'Article body edit', 'Sub title', true);

        $this->assertEquals('My Article Edit', $article->title);
        $this->assertEquals('Article body edit', $article->body);
        $this->assertEquals('Sub title', $article->sub_title);
        $this->assertTrue($article->headliner);
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
    public function get_newest_article_for_a_category()
    {
        $category1 = Category::create([
            'title' => 'PHP',
            'description' => 'Articles related to PHP'
        ]);

        $PHPArticleNewest = Article::create([
            'title' => 'My PHP Article',
            'body' => 'Article body'
        ]);

        $PHPArticleNewest->associateCategory($category1);

        $PHPArticleOlder = Article::create([
            'title' => 'My JS Article',
            'body' => 'Article body'
        ]);

        $PHPArticleOlder->associateCategory($category1);
        $PHPArticleOlder->created_at = date('Y-m-d H:i:s', time() - (60 * 60 * 24 * 2));
        $PHPArticleOlder->save();

        $categoryArticleRetriever = new RecentArticle($category1, new ArticleRetrieverOutput);
        $categoryWithRecentArticle = $categoryArticleRetriever->get();

        $this->assertEquals($PHPArticleNewest->id, $categoryWithRecentArticle["article"]->id);
    }
    
    /** @test */
    public function can_feature_an_article()
    {
        $article = FeaturedArticle::fromForm('My Article', 'Article body');

        $article->setSortOrder(1);

        $this->assertTrue(FeaturedArticle::all()->contains($article));
    }

    /** @test */
    public function can_remove_featured_article()
    {
        $article = FeaturedArticle::fromForm('My Article', 'Article body');
        $article2 = FeaturedArticle::fromForm('My Article 2', 'Article body');
        $article3 = FeaturedArticle::fromForm('My Article 3', 'Article body');

        $article->setSortOrder(1);
        $article2->setSortOrder(2);
        $article3->setSortOrder(3);

        $article->removeFromFeaturedArticles();

        $this->assertEquals(1, $article2->order);
        $this->assertEquals(2, $article3->order);
    }
}
