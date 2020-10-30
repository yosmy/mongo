<?php

namespace Yosmy\Mongo;

use Traversable;

interface ManageCollection
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getTypeMap(): array;

    /**
     * @param array|object $key
     * @param array $options
     *
     * @return string
     */
    public function createIndex($key, array $options = []): string;

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return Collection
     */
    public function find($filter = [], array $options = []): Collection;

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return array|object|null
     */
    public function findOne($filter = [], array $options = []);

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return integer
     */
    public function count($filter = [], array $options = []): int;

    /**
     * @param array $pipeline
     * @param array $options
     *
     * @return Traversable
     */
    public function aggregate(array $pipeline, array $options = []): Traversable;

    /**
     * @param array|object $document
     * @param array        $options
     *
     * @return InsertOneResult
     */
    public function insertOne($document, array $options = []): InsertOneResult;

    /**
     * @param array|object $document
     * @param array        $options
     *
     * @return InsertOneResult
     *
     * @throws DuplicatedKeyException
     */
    public function insertOneUnique($document, array $options = []): InsertOneResult;

    /**
     * @param array[]|object[] $documents
     * @param array            $options
     *
     * @return InsertManyResult
     */
    public function insertMany(array $documents, array $options = []): InsertManyResult;

    /**
     * @param array|object $filter
     * @param array|object $update
     * @param array        $options
     *
     * @return UpdateResult
     */
    public function updateMany($filter, $update, array $options = []): UpdateResult;

    /**
     * @param array|object $filter
     * @param array|object $update
     * @param array        $options
     *
     * @return UpdateResult
     */
    public function updateOne($filter, $update, array $options = []): UpdateResult;

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return DeleteResult
     */
    public function deleteOne($filter, array $options = []): DeleteResult;

    /**
     * @param array|object $filter
     * @param array        $options
     *
     * @return DeleteResult
     */
    public function deleteMany($filter, array $options = []): DeleteResult;

    /**
     * @param string $name
     * @param string $content
     */
    public function uploadFile(
        string $name,
        string $content
    );

    /**
     * @param array $options
     *
     * @return array|object
     */
    public function drop(array $options = []);
}
