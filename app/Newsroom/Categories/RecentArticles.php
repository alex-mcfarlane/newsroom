<?php

namespace App\Newsroom\Categories;

/**
 * Description of RecentArticles
 *
 * @author Alex McFarlane
 */
class RecentArticles extends CategoryArticleRetriever
{
    public function retrieval()
    {
        $output = $this->model->toArray();
        $output["articles"] = [];
        $output["articles"][] = $this->query->newestArticles($this->limit);
        
        return $output;
    }
}