<?php

namespace Yosmy\Mongo\Collection;

/**
 * @di\service({
 *     private: true
 * })
 */
class RegisterChanges
{
    /**
     * @var string[]
     */
    private $changes;

    /**
     * @param string $key
     */
    public function register(string $key)
    {
        $this->changes[$key] = true;
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        $keys = array_keys($this->changes);

        ksort($keys);

        return $keys;
    }
}
