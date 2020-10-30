<?php

namespace Yosmy\Mongo;

use LogicException;

/**
 * @di\service()
 */
class JoinAggregation
{
    /**
     * @param array $first
     * @param array $seconds
     *
     * @return array
     */
    public function join(
        array $first,
        array $seconds
    ): array {
        foreach ($seconds as $second) {
            if (
                isset($second['year'])
                && $second['year'] != $first['year']
            ) {
                continue;
            }

            if (
                isset($second['month'])
                && $second['month'] != $first['month']
            ) {
                continue;
            }

            if (
                isset($second['day'])
                && $second['day'] != $first['day']
            ) {
                continue;
            }

            return $second;
        }

        throw new LogicException();
    }
}
