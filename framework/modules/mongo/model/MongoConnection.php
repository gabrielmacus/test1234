<?php

/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 22/12/2017
 * Time: 02:22 PM
 */


class MongoConnection
{
    use Magic;

    protected $user;
    protected $host;
    protected $password;
    protected $db;
    protected $port;
    protected $client;

    /**
     * MongoConnection constructor.
     * @param $user
     * @param $password
     * @param $db
     * @param $port
     */
    public function __construct($db,$user="", $password="",$host="localhost", $port=27017 )
    {
        $this->user = $user;
        $this->password = $password;
        $this->db = $db;
        $this->port = $port;
        $this->host = $host;

    }

    public function connect()
    {
        if(empty($this->client) || empty($this->client->connected))
        {
            if($this->password && $this->user)
            {
                $uri = "mongodb://{$this->user}:{$this->password}@{$this->host}:{$this->port}";

            }
            else
            {
                $uri = "mongodb://{$this->host}:{$this->port}";

            }


            $this->client=new MongoClient($uri);

            if(!$this->client->connect())
            {
                throw new MongoConnectionException("mongodb.error.connecting",500);
            }

            $this->client= $this->client->selectDB($this->db);
        }
    }

    public function client()
    {
        if(!$this->client)
        {
            throw new MongoConnectionException("mongodb.error.notConnected",500);
        }

        return $this->client;
    }

}

