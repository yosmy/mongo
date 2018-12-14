## Insert

Always insert as array, because the update is always as array?

```
$this->manageCollection->insertOne([
    '_id': $id,
    ...
]);
```

## Id

Don't use _id inside object

```
/**
 * {@inheritdoc}
 */
public function bsonSerialize()
{
    $data = $this->getArrayCopy();

    $data['_id'] = $data['id'];

    unset($data['id']);

    return (object) $data;
}

/**
 * {@inheritdoc}
 */
public function bsonUnserialize(array $data)
{
    $data['id'] = $data['_id'];
    unset($data['_id']);

    parent::bsonUnserialize($data);
}
```

## Datetime

Use timestamp

```
$object = new Object(
    uniqid(),
    time()
);
```

```
/**
 * {@inheritdoc}
 */
public function bsonSerialize()
{
    $created = new UTCDateTime($this->offsetGet('created') * 1000);

    return (object) array_merge(
        parent::getArrayCopy(),
        // Override
        [
            'created' => $created,
        ]
    );
}

/**
 * {@inheritdoc}
 */
public function bsonUnserialize(array $data)
{
    $data['id'] = $data['_id'];
    unset($data['_id']);

    /** @var UTCDateTime $created */
    $created = $data['created'];
    $data['created'] = $created->toDateTime()->getTimestamp();

    parent::bsonUnserialize($data);
}
```

## Array

```
/**
 * {@inheritdoc}
 */
public function bsonUnserialize(array $data)
{
    $this->response = json_decode(json_encode($data['response']), true);
}
