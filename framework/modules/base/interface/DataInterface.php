<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/12/2017
 * Time: 20:34
 */

/**
 * Interface DataInterface
 *
 *
 *
 */
interface DataInterface
{

    public function save(Base $base);

    public function delete(Base $base);

    public function read(Base $base);

    public  function beforeSave(Base &$base);

    public  function afterSave(Base &$base);

    public function beforeDelete(Base &$base);

    public function afterDelete(Base &$base);

    public static function ObjectFromArray(Array $array);


}