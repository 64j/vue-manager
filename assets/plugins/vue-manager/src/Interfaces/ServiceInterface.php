<?php

namespace VueManager\Interfaces;

interface ServiceInterface
{
    /**
     * @param array $params
     * @return array
     */
    public function create(array $params = []): array;

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function read(array $params = []): array;

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function update(array $params = []): array;

    /**
     * @param array $params
     * @return array
     * @throws \VueManager\Exceptions\NotFoundException
     */
    public function delete(array $params = []): array;

    /**
     * @param array $params
     * @return array
     */
    public function list(array $params = []): array;
}
