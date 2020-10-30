<?php

namespace Yosmy\Mongo;

use MongoDB;

class DeleteResult
{
    /**
     * @var MongoDB\DeleteResult
     */
    private $result;

    /**
     * @param MongoDB\DeleteResult $result
     */
    public function __construct(MongoDB\DeleteResult $result)
    {
        $this->result = $result;
    }
}