<?php

namespace App\Newsroom\Articles;

use App\Category;
use App\Newsroom\Categories\CategoryArticleRetrieverFactory;

/**
 * Description of ArticleRetrieverService
 *
 * @author Alex McFarlane
 */
class ArticleRetrieverService {
    
    public function retrieveArticlesForCategories($categories, $limit = null)
    {
        $output = [];

        foreach($categories as $category)
        {
            //I should use a formatter for this logic... not the responsibility of this class to create the map
            $result = []; //map that will hold categories and their articles
            $result[$category->title] = $this->retrieveArticlesForCategory($category, $limit);
            
            if(count($result) > 0)
            {
                $output = $output + $result; //TODO: Why won't array_merge($output, $result) work for union?   
            }
        }

        return $output;
    }

    public function retrieveArticlesForCategory(Category $category, $limit = null)
    {   
        $retriever = CategoryArticleRetrieverFactory::create($category, $limit);
        
        return $retriever->get();
    }
}