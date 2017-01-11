<?php

namespace App\Newsroom\Categories;

/**
 * Description of RecentArticle
 *
 * @author Alex McFarlane
 */
class RecentArticle extends CategoryArticleRetriever
{    
    public function retrieval()
    {        
        $output = $this->model->toArray();
        $output["articles"] = [];
        $output["articles"][] = $this->query->newestArticle();
        
        return $output;
    }
}