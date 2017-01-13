<?php
namespace App\Newsroom\Exceptions;

/**
 * Description of ArticleException
 *
 * @author Alex McFarlane
 */
class ArticleException extends BaseException
{
    public function __construct($errors, $httpStatusCode = 400)
    {
        parent::__construct("Article exception", $errors, $httpStatusCode);
    }
}
