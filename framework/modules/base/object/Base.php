<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/12/2017
 * Time: 20:35
 */
class Base  implements ArrayAccess, JsonSerializable
{
    use Magic;
    public function __construct()
    {
    }

    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {

        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    public function printSerialize()
    {
        $arr=[];
        $i=0;
        foreach ($this as $k=>$v)
        {

            $arr[$k]=$v;

            $i++;


        }

        return $arr;
    }

    public function validate()
    {

    }

    function jsonSerialize()
    {
        $json = [];
        foreach ($this as  $k=>$v)
        {
            $json[$k]=$v;
        }

        return $json;
    }



}