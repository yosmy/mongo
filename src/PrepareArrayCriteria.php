<?php

namespace Yosmy\Mongo;

/**
 * @di\service()
 */
class PrepareArrayCriteria
{
    /**
     * @param string $key
     * @param array  $values
     *
     * @return array
     */
    public function prepare(
        string $key,
        array $values
    ): array {
        $criteria = [];

        foreach ($values as $k=> $v) {
            if (!$v) {
                continue;
            }

            $criteria[sprintf("%s.%s", $key, $k)] = $v;
        }

        return $criteria;
    }
}
