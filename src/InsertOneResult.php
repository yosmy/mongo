<?php

namespace Yosmy\Mongo;

use MongoDB;

class InsertOneResult
{
    /**
     * @var MongoDB\InsertOneResult
     */
    private $result;

    /**
     * @param MongoDB\InsertOneResult $result
     */
    public function __construct(MongoDB\InsertOneResult $result)
    {
        $this->result = $result;
    }
}