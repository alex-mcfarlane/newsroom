<?php
namespace App\Newsroom\Exceptions;

/**
 * Description of ArticleException
 *
 * @author Alex McFarlane
 */
class ArticleException extends BaseException
{
    public function __construct($errors)
    {
        parent::__construct("Article exception", $errors);
    }
}
