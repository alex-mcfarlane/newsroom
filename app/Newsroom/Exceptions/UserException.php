<?php
namespace App\Newsroom\Exceptions;

/**
 * Description of ArticleException
 *
 * @author Alex McFarlane
 */
class UserException extends BaseException
{
    public function __construct($errors, $httpStatusCode = 400)
    {
        parent::__construct("User exception", $errors, $httpStatusCode);
    }
}
