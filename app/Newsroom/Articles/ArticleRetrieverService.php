<?php

namespace App\Newsroom\Articles;

use App\Category;
use App\Newsroom\Articles\ArticleRetieverFactory;
/**
 * Description of ArticleRetrieverService
 *
 * @author Alex McFarlane
 */
class ArticleRetrieverService {
    
    public function retrieveArticlesForCategories()
    {
        $categories = Category::all();
        $newestArticlesPerCategory = []; //hashmap that will hold categories and their articles
        
        foreach($categories as $category)
        {
            $retriever = ArticleRetrieverFactory::create($category, $request->input('limit'));
            $result = $retriever->get();
            
            if($result)
            {
                $newestArticlesPerCategory[$category->title] = $result;
            }
        }

    	$newestArticlesPerCategory;
    }
}