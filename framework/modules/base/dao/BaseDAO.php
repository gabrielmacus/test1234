<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/12/2017
 * Time: 20:45
 */
class BaseDAO implements DataInterface,MongoDataInterface
{
    protected $mongoConnection;

    public function save(Base $base)
    {

        $base->validate();

        $this->mongoConnection->connect();

        $collection = $this->mongoConnection->client()->objects;

        $data = $base->jsonSerialize() + ["_type"=>get_class($base)];

        $this->beforeSave($base);

        if(!$collection->save($data))
        {
            throw new BaseException("save");
        }

        $base->_id = $data["_id"];

        $this->afterSave($base);
    }

    public function delete(Base $base)
    {
        $this->mongoConnection->connect();

        $collection = $this->mongoConnection->client()->objects;

        $data = $base->jsonSerialize() + ["_type"=>get_class($base)];

        $this->beforeDelete($base);

        if(!$collection->remove($data))
        {
            throw new BaseException("delete");
        }

        $this->afterDelete($base);
    }

    public function beforeDelete(Base &$base)
    {
        // TODO: Implement beforeDelete() method.
    }

    public function afterDelete(Base &$base)
    {
        // TODO: Implement afterDelete() method.
    }

    public function read(Base $base)
    {
        //Results feteched
        $results = [];

        $this->mongoConnection->connect();

        $collection=$this->mongoConnection->client()->objects;

        //Query array
        $query = $base->jsonSerialize();

        if(get_class($base) != "Base")
        {
            $query = $query  + ["_type"=>get_class($base)];
        }

        $cursor = $collection->find($query);

        foreach ($cursor as $k=>$v)
        {
            $results[strval($v["_id"])]= BaseDAO::ObjectFromArray($v);
        }



        return $results;
    }

    public function beforeSave(Base &$base)
    {
        // TODO: Implement beforeSave() method.
    }

    public function afterSave(Base &$base)
    {
        // TODO: Implement afterSave() method.
    }

    public static function ObjectFromArray(Array $array)
    {
        $base = new $array["_type"]();

        foreach ($array as $k=>$v)
        {
            $base[$k]=$v;
        }

        return $base;
    }

    public function __construct(MongoConnection $mongoConnection)
    {
        $this->mongoConnection = $mongoConnection;
    }

    function associate(Array $associations)
    {

        $this->mongoConnection->connect();

        $collection=$this->mongoConnection->client()->objects;

        $_associations=[];

        foreach ($associations as $value)
        {
            $association = new Base();

            $association->type1 = get_class($value[0]);

            $association->id1 = $value[0]->_id;

            $association->type2 = get_class($value[1]);

            $association->id2 = $value[1]->_id;

            $association->_name=$value[2];

            $association->_type ="Association";

            if(!empty($value[3]) && is_array($value[3]))
            {
                $association["extra"] = $value[3];
            }

            $_associations[] = $association->jsonSerialize();

        }

        $collection->batchInsert($_associations);
    }


    public function readAssociations(Array &$parentObjects, $maxLevel = 3, $objects = false, Array &$results = [], $level = 0)
    {
        if(!$objects)
        {
            $objects = $parentObjects;
        }

        $level++;

        $this->mongoConnection->connect();

        $collection=$this->mongoConnection->client()->objects;

        $ids = [];


        foreach ($objects as $object)
        {

            if(is_a($object,"Base") && !empty($object["_id"]))
            {
                $ids[]=$object["_id"];
            }

        }

        $associatedIds=[];
        $associationsMap=[];
        $associatedObjects=[];

        $associations = $collection->find
        (
            [
                '_type'=> "Association",
                "id1"=>['$in'=> $ids]
            ]
        );
        foreach ($associations as $key => $value)
        {

            $associationsMap[strval($value["id2"])][strval($value["id1"])]=$value["_name"];

            $associatedIds[]=$value["id2"];

        }

        $associatedCursors = $collection->find(['_id'=>['$in'=>$associatedIds]]);

        foreach ($associatedCursors as $key => $value)
        {
            $id=strval($value["_id"]);

            $objectFromArray = BaseDAO::ObjectFromArray($value);

            $associatedObjects[$id] =  $objectFromArray;

            if(!empty($associationsMap[$id]))
            {
                foreach ($associationsMap[$id] as $k=>$v)
                {

                    $results[$id][$v]= $k;
                }

                $results[$id]["_object"]=$objectFromArray;

            }


        }

        if($level<$maxLevel && count($associatedObjects) > 0)
        {

            return $this->readAssociations($parentObjects,$maxLevel,$associatedObjects,$results,$level);


        }



        foreach ($results as $i=>$j)
        {

            $b = $j["_object"];

            unset($j["_object"]);

            foreach ($j as $k=>$l)
            {

                if(!empty($results[$l]))
                {



                    if(empty($results[$l]["_object"][$k]))
                    {
                        $results[$l]["_object"][$k]=[];
                    }
                    $arr = $results[$l]["_object"][$k];

                    $arr[strval($b["_id"])] = $b;

                    $results[$l]["_object"][$k]=$arr;

                }
            }

        }


        foreach ($results as $i=>$j)
        {

            $b = $j["_object"];

            unset($j["_object"]);

            foreach ($j as $k=>$l)
            {


                if(!empty($parentObjects[$l]))
                {

                    if(empty($parentObjects[$l][$k]))
                    {
                        $parentObjects[$l][$k]=[];
                    }

                    $arr = $parentObjects[$l][$k];

                    $arr[strval($b["_id"])] = $b;

                    $parentObjects[$l][$k]=$arr;


                }
            }

        }
    }
}