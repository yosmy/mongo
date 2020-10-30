<?php

namespace Yosmy\Mongo;

use MongoDB;
use Traversable;
use LogicException;

class BaseManageCollection implements ManageCollection
{
    /**
     * @var MongoDB\Collection
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
        $this->collection = (new MongoDB\Client($uri))
            ->selectCollection(
                $db,
                $collection,
                $options
            );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->collection->getCollectionName();
    }

    /**
     * @return array
     */
    public function getTypeMap(): array
    {
        return $this->collection->getTypeMap();
    }

    /**
     * @param array|object $key
     * @param array $options
     *
     * @return string
     */
    public function createIndex($key, array $options = []): string
    {
        return $this->collection->createIndex($key, $options);
    }

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return Collection
     */
    public function find($filter = [], array $options = []): Collection
    {
        $cursor = $this->collection->find($filter, $options);

        return new Collection($cursor);
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
    public function count($filter = [], array $options = []): int
    {
        return $this->collection->countDocuments($filter, $options);
    }

    /**
     * @param array $pipeline
     * @param array $options
     *
     * @return Traversable
     */
    public function aggregate(array $pipeline, array $options = []): Traversable
    {
        return $this->collection->aggregate($pipeline, $options);
    }

    /**
     * @param array|object $document
     * @param array        $options
     *
     * @return InsertOneResult
     */
    public function insertOne($document, array $options = []): InsertOneResult
    {
        try {
            $result = $this->collection->insertOne($document, $options);
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            throw new LogicException($e->getMessage(), $e->getCode(), $e);
        }

        return new InsertOneResult($result);
    }

    /**
     * @param array|object $document
     * @param array        $options
     *
     * @return InsertOneResult
     *
     * @throws DuplicatedKeyException
     */
    public function insertOneUnique($document, array $options = []): InsertOneResult
    {
        try {
            $result = $this->collection->insertOne($document, $options);
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            throw new DuplicatedKeyException();
        }

        return new InsertOneResult($result);
    }

    /**
     * @param array[]|object[] $documents
     * @param array            $options
     *
     * @return InsertManyResult
     */
    public function insertMany(array $documents, array $options = []): InsertManyResult
    {
        $result = $this->collection->insertMany($documents, $options);

        return new InsertManyResult($result);
    }

    /**
     * @param array|object $filter
     * @param array|object $update
     * @param array        $options
     *
     * @return UpdateResult
     */
    public function updateMany($filter, $update, array $options = []): UpdateResult
    {
        $result = $this->collection->updateMany($filter, $update, $options);

        return new UpdateResult($result);
    }

    /**
     * @param array|object $filter
     * @param array|object $update
     * @param array        $options
     *
     * @return UpdateResult
     */
    public function updateOne($filter, $update, array $options = []): UpdateResult
    {
        $result = $this->collection->updateOne($filter, $update, $options);

        return new UpdateResult($result);
    }

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return DeleteResult
     */
    public function deleteOne($filter, array $options = []): DeleteResult
    {
        $result = $this->collection->deleteOne($filter, $options);

        return new DeleteResult($result);
    }

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return DeleteResult
     */
    public function deleteMany($filter, array $options = []): DeleteResult
    {
        $result = $this->collection->deleteMany($filter, $options);

        return new DeleteResult($result);
    }

    /**
     * @param string $name
     * @param string $content
     */
    public function uploadFile(
        string $name,
        string $content
    ) {
        $bucket = (new MongoDB\Client)
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
