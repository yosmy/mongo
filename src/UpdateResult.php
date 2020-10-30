<?php

namespace Yosmy\Mongo;

use MongoDB;

class UpdateResult
{
    /**
     * @var MongoDB\UpdateResult
     */
    private $result;

    /**
     * @param MongoDB\UpdateResult $result
     */
    public function __construct(MongoDB\UpdateResult $result)
    {
        $this->result = $result;
    }

    /**
     * @return integer|null
     */
    public function getModifiedCount(): ?int
    {
        return $this->result->getModifiedCount();
    }

    /**
     * @return integer
     */
    public function getMatchedCount(): ?int
    {
        return $this->result->getMatchedCount();
    }
}