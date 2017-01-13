<?php

namespace App\Newsroom\Exceptions;

/**
 * CategoryException
 *
 * @author Alex McFarlane
 */

class CategoryException extends BaseException
{
	public function __construct($errors, $httpStatusCode = 400)
	{
		parent::__construct('Category exception', $errors, $httpStatusCode);
	}
}