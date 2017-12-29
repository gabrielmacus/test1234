<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/12/2017
 * Time: 21:21
 */
interface MongoDataInterface
{
    public function __construct(MongoConnection $mongoConnection);

    public function associate(Array $associations);

    public function readAssociations(Array &$parentObjects, $maxLevel=3,$objects=false,Array &$results=[],$level=0);


}