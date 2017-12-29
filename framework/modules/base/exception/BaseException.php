<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/12/2017
 * Time: 20:42
 */
class BaseException extends Exception
{

    public function __construct($message = "", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}