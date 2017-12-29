<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 22/12/2017
 * Time: 18:23
 */
trait Magic
{

    public function __get( $key )
    {
        return $this->$key;
    }

    public function __set( $key, $value )
    {
        $this->$key = $value;
    }


}