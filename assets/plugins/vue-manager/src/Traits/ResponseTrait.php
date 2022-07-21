<?php

namespace VueManager\Traits;

use VueManager\Interfaces\ArrayableInterface;

trait ResponseTrait
{
    /**
     * @param $data
     * @param array $meta
     * @return array
     */
    public function ok($data, array $meta = []): array
    {
        if ($data instanceof ArrayableInterface) {
            $data = $data->toArray();
        }

        return [
            'data' => $data,
            'meta' => $meta,
        ];
    }
}
