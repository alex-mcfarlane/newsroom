<?php

namespace App\Newsroom\Exceptions;

/**
 * ImageException
 *
 * @author Alex McFarlane
 */

class ImageException extends BaseException
{
	public function __construct($errors, $httpStatusCode = 400)
	{
		parent::__construct('Image exception', $errors, $httpStatusCode);
	}
}