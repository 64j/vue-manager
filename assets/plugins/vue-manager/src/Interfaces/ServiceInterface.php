<?php

namespace VueManager\Interfaces;

interface ServiceInterface
{
    /**
     * @param array $params
     */
    public function create(array $params = []);

    /**
     * @param array $params
     */
    public function read(array $params = []);

    /**
     * @param array $params
     */
    public function update(array $params = []);

    /**
     * @param array $params
     */
    public function delete(array $params = []);

    /**
     * @param array $params
     */
    public function copy(array $params = []);

    /**
     * @param array $params
     * @return array
     */
    public function list(array $params = []): array;

    /**
     * @return array
     */
    public function getMeta(): array;
}
