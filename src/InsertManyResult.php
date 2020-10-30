<?php

namespace Yosmy\Mongo;

use MongoDB;

class InsertManyResult
{
    /**
     * @var MongoDB\InsertManyResult
     */
    private $result;

    /**
     * @param MongoDB\InsertManyResult $result
     */
    public function __construct(MongoDB\InsertManyResult $result)
    {
        $this->result = $result;
    }
}