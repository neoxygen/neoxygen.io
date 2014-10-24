<?php

namespace Neoxygen\AppBundle\Graph;

use Neoxygen\AppBundle\Service\Neo4jService;

class BaseNode
{
    protected $state;

    public function isNew()
    {
        return null === $this->state;
    }

    public function getConnection()
    {
        $conn = Neo4jService::getNeo4jConnection();

        return $conn;
    }
}