<?php

namespace App\Newsroom\Exceptions;

use Exception;

/**
 * Base exception class
 */
class BaseException extends Exception{
    
    protected $errors;
    protected $httpStatusCode;

	public function __construct($message, $errors, $httpStatusCode = 400, $code = 0, Exception $previous = null)
	{
		$this->errors =  $errors;
        $this->httpStatusCode = $httpStatusCode;
		parent::__construct($message, $code, $previous);
	}

	public function getErrors()
	{
		return $this->errors;
	}
    
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
