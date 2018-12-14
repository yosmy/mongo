<?php

namespace Yosmy\Mongo;

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\UTCDateTimeInterface;
use MongoDB\BSON\Persistable;
use JsonSerializable;

class DateTime implements Persistable, UTCDateTimeInterface, JsonSerializable
{
    /**
     * @var UTCDateTime
     */
    private $utc;

    /**
     * @param integer|null $milliseconds
     */
    public function __construct(
        $milliseconds = null
    ) {
        $this->utc = new UTCDateTime($milliseconds);
    }

    /**
     * {@inheritDoc}
     */
    public function bsonSerialize()
    {
        return [
            'utc' => $this->utc
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bsonUnserialize($data)
    {
        $this->utc = $data['utc'];
    }

    /**
     * {@inheritDoc}
     */
    public function toDateTime()
    {
        return $this->utc->toDateTime();
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->utc->toDateTime()->getTimestamp();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->utc->__toString();
    }
}