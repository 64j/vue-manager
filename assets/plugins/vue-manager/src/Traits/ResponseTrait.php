<?php

namespace VueManager\Traits;

trait ResponseTrait
{
    /**
     * @param array $data
     * @param array $meta
     * @return array
     */
    public function ok(array $data = [], array $meta = []): array
    {
        return [
            'data' => $data,
            'meta' => $meta,
        ];
    }
}
