<?php

namespace VueManager\Interfaces;

interface ServiceInterface
{
    /**
     * @param array $params
     * @return \VueManager\Interfaces\ModelInterface
     */
    public function create(array $params = []): ModelInterface;

    /**
     * @param array $params
     * @return \VueManager\Interfaces\ModelInterface
     */
    public function read(array $params = []): ModelInterface;

    /**
     * @param array $params
     * @return \VueManager\Interfaces\ModelInterface
     */
    public function update(array $params = []): ModelInterface;

    /**
     * @param array $params
     * @return \VueManager\Interfaces\ModelInterface
     */
    public function delete(array $params = []): ModelInterface;

    /**
     * @param array $params
     * @return \VueManager\Interfaces\ModelInterface
     */
    public function copy(array $params = []): ModelInterface;

    /**
     * @param array $params
     * @return ModelInterface[]|array
     */
    public function list(array $params = []): array;

    /**
     * @return array
     */
    public function getMeta(): array;
}
