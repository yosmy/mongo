<?php

namespace Yosmy\Mongo;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\DeleteResult;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\InsertManyResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;
use Traversable;
use LogicException;

/**
 * @di\service({
 *     private: true
 * })
 */
class ManageCollection
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param string $uri
     * @param string $db
     * @param string $collection
     * @param array  $options
     */
    public function __construct(
        string $uri,
        string $db,
        string $collection,
        array $options
    ) {
        $this->collection = (new Client($uri))
            ->selectCollection(
                $db,
                $collection,
                $options
            );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->collection->getCollectionName();
    }

    /**
     * @return array
     */
    public function getTypeMap()
    {
        return $this->collection->getTypeMap();
    }

    /**
     * @param array|object $key
     * @param array $options
     *
     * @return string
     */
    public function createIndex($key, array $options = [])
    {
        return $this->collection->createIndex($key, $options);
    }

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return Cursor
     */
    public function find($filter = [], array $options = [])
    {
        return $this->collection->find($filter, $options);
    }

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return array|object|null
     */
    public function findOne($filter = [], array $options = [])
    {
        return $this->collection->findOne($filter, $options);
    }

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return integer
     */
    public function count($filter = [], array $options = [])
    {
        return $this->collection->countDocuments($filter, $options);
    }

    /**
     * @param array $pipeline
     * @param array $options
     *
     * @return Traversable
     */
    public function aggregate(array $pipeline, array $options = [])
    {
        return $this->collection->aggregate($pipeline, $options);
    }

    /**
     * @param array|object $document
     * @param array        $options
     *
     * @return InsertOneResult
     */
    public function insertOne($document, array $options = [])
    {
        try {
            return $this->collection->insertOne($document, $options);
        } catch (BulkWriteException $e) {
            throw new LogicException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array|object $document
     * @param array        $options
     *
     * @return InsertOneResult
     *
     * @throws DuplicatedKeyException
     */
    public function insertOneUnique($document, array $options = [])
    {
        try {
            return $this->collection->insertOne($document, $options);
        } catch (BulkWriteException $e) {
            throw new DuplicatedKeyException();
        }
    }

    /**
     * @param array[]|object[] $documents
     * @param array            $options
     *
     * @return InsertManyResult
     */
    public function insertMany(array $documents, array $options = [])
    {
        return $this->collection->insertMany($documents, $options);
    }

    /**
     * @param array|object $filter
     * @param array|object $update
     * @param array        $options
     *
     * @return UpdateResult
     */
    public function updateMany($filter, $update, array $options = [])
    {
        return $this->collection->updateMany($filter, $update, $options);
    }

    /**
     * @param array|object $filter
     * @param array|object $update
     * @param array        $options
     *
     * @return UpdateResult
     */
    public function updateOne($filter, $update, array $options = [])
    {
        return $this->collection->updateOne($filter, $update, $options);
    }

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return DeleteResult
     */
    public function deleteOne($filter, array $options = [])
    {
        return $this->collection->deleteOne($filter, $options);
    }

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return DeleteResult
     */
    public function deleteMany($filter, array $options = [])
    {
        return $this->collection->deleteMany($filter, $options);
    }

    /**
     * @param string $name
     * @param string $content
     */
    public function uploadFile(
        string $name,
        string $content
    ) {
        $bucket = (new Client)
            ->selectDatabase(
                $this->collection->getDatabaseName()
            )
            ->selectGridFSBucket([
                'bucketName' => 'yosmy_avatar'
            ]);

        $stream = $bucket->openUploadStream($name);

        fwrite($stream, $content);

        fclose($stream);
    }

    /**
     * @param array $options
     *
     * @return array|object
     */
    public function drop(array $options = [])
    {
        if (!$options) {
            $options = [
                'typeMap' => [
                    'root' => 'array',
                ],
            ];
        }

        return $this->collection->drop($options);
    }
}
