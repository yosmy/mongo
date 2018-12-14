<?php

namespace Yosmy\Mongo;

use Exception;
use JsonSerializable;

class DuplicatedKeyException extends Exception implements JsonSerializable
{
    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            'message' => $this->getMessage()
        ];
    }

}
