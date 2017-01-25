<?php

namespace App\Newsroom\Articles;

use App\Category;
use App\Newsroom\Categories\CategoryArticleRetrieverFactory;
use App\Newsroom\Categories\CategoryArticleRetrieverOutput;

/**
 * Description of ArticleRetrieverService
 *
 * @author Alex McFarlane
 */
class ArticleRetrieverService {
    
    public function retrieveArticlesForCategories($categories, $limit = 0)
    {
        $output = [];

        foreach($categories as $category)
        {
            $result = $this->retrieveArticlesForCategory($category, $limit);
            $output = $output + $result; //TODO: Why won't array_merge($output, $result) work for union?
        }

        return $output;
    }

    public function retrieveArticlesForCategory(Category $category, $limit = 0)
    {
        $newestArticlesPerCategory = []; //map that will hold categories and their articles
        
        $retriever = CategoryArticleRetrieverFactory::create($category, $limit);
        $result = $retriever->get(new CategoryArticleRetrieverOutput());

        if($result)
        {
            $newestArticlesPerCategory[$category->title] = $result;
        }

    	return $newestArticlesPerCategory;
    }
}