<?php

namespace Yosmy\Mongo;

use StdClass;

class NormalizeStd
{
    /**
     * @param StdClass $data
     *
     * @return array
     */
    static public function normalize(
        StdClass $data
    ): array {
        return json_decode(json_encode($data), true);
    }
}
