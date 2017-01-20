<?php

namespace App\Newsroom\Articles;

use App\Category;
use App\Newsroom\Articles\ArticleRetieverFactory;
use App\Newsroom\Articles\CategoryArticleRetrieverOutput;

/**
 * Description of ArticleRetrieverService
 *
 * @author Alex McFarlane
 */
class ArticleRetrieverService {
    
    public function retrieveArticlesForCategories($limit = null)
    {
        $categories = Category::all();
        $newestArticlesPerCategory = []; //hashmap that will hold categories and their articles
        
        foreach($categories as $category)
        {
            $retriever = ArticleRetrieverFactory::create($category, $limit);
            $result = $retriever->get(new CategoryArticleRetrieverOutput());

            if($result)
            {
                $newestArticlesPerCategory[$category->title] = $result;
            }
        }

    	return $newestArticlesPerCategory;
    }
}