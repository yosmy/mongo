<?php

namespace Yosmy\Mongo;

use MongoDB\Driver\Cursor as BaseCursor;
use IteratorAggregate;
use JsonSerializable;
use Traversable;
use Exception;
use LogicException;

class Collection implements IteratorAggregate, JsonSerializable
{
    /**
     * @var JsonSerializable[]
     */
    protected $cursor;

    /**
     * @param Traversable $cursor
     * @param string|null $type
     */
    public function __construct(
        Traversable $cursor,
        string $type = null
    ) {
        if ($type) {
            /** @var BaseCursor $cursor */
            $cursor->setTypeMap(['root' => $type]);
        }

        $this->cursor = $cursor;
    }

    /**
     * @return BaseCursor
     */
    public function getIterator()
    {
        try {
            return $this->cursor;
        } catch (Exception $e) {
            throw new LogicException();
        }
    }

    /**
     * @param array $typemap
     */
    public function setTypeMap(
        array $typemap
    ) {
        $this->cursor->setTypeMap($typemap);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $items = [];
        foreach ($this->cursor as $item) {
            $items[] = $item->jsonSerialize();
        }

        return $items;
    }
}
