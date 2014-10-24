<?php

namespace Neoxygen\AppBundle\Service;

use Neoxygen\NeoClient\Client as Neo4jClient;

class Neo4jService
{
    private static $client;

    public static function getNeo4jConnection()
    {
        if (null === self::$client){
            self::$client = new Neo4jClient();
            self::$client->addConnection('default', 'http', 'localhost', 7474);
            self::$client->build();
        }

        return self::$client;
    }
}