<?php

declare(strict_types=1);

namespace VueManager\Models;

use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;
use VueManager\Interfaces\ArrayableInterface;

abstract class AbstractModel implements JsonSerializable, ArrayableInterface
{
    /**
     * @var array
     */
    protected array $__exceptedKeys = [];

    /**
     * @var array
     */
    protected array $__onlyKeys = [];

    /**
     * @var array
     */
    public array $__meta = [];

    /**
     * @param array $params
     * @return self
     */
    public function hydrate(array $params = []): self
    {
        $class = new ReflectionClass($this);

        $params = $this->convert($params);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $name = $reflectionProperty->getName();
            $setter = 'set' . ucfirst($name);

            if (isset($params[$name])) {
                $reflectionProperty->setValue($this, $params[$name]);

                if (is_callable([$this, $setter])) {
                    $this->$setter($reflectionProperty->getValue($this));
                }
            } else {
                unset($this->{$name});
            }
        }

        foreach ($params as $key => $value) {
            if (!isset($this->{$key})) {
                $this->__meta[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * @param array $params
     * @return array
     */
    public function convert(array $params = []): array
    {
        return $this->toCamel($params);
    }

    /**
     * @param array $params
     * @return array
     */
    protected function toCamel(array $params = []): array
    {
        $fn = fn($c) => strtoupper($c[1]);

        foreach ($params as $key => $value) {
            $k = preg_replace_callback('/_([a-z])/', $fn, $key);

            if (!isset($params[$k])) {
                unset($params[$key]);
                $params[$k] = $value;
            }
        }

        return $params;
    }

    /**
     * @param array $params
     * @return array
     */
    protected function fromCamel(array $params = []): array
    {
        $fn = fn($c) => '_' . strtolower($c[1]);

        foreach ($params as $key => $value) {
            $k = preg_replace_callback('/([A-Z])/', $fn, $key);

            if (!isset($params[$k])) {
                unset($params[$key]);
                $params[$k] = $value;
            }
        }

        return $params;
    }

    /**
     * @param array $keys
     * @return $this
     */
    public function except(array $keys): self
    {
        $this->__exceptedKeys = $keys;

        return $this;
    }

    /**
     * @param array $keys
     * @return $this
     */
    public function only(array $keys): self
    {
        $this->__onlyKeys = $keys;

        return $this;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return json_encode($this->__toArray());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->__toArray();
    }

    /**
     * @return array
     */
    public function toData(): array
    {
        return (array) evolutionCMS()->db->escape($this->__toArray());
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        $data = get_object_vars($this);

        foreach ($this->__exceptedKeys as $key) {
            if (array_key_exists($key, $data)) {
                unset($data[$key]);
            }
        }

        if ($this->__onlyKeys) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->__onlyKeys)) {
                    unset($data[$key]);
                }
            }
        }

        $this->__exceptedKeys = [];
        $this->__onlyKeys = [];

        unset($data['__exceptedKeys']);
        unset($data['__onlyKeys']);
        unset($data['__meta']);

        return $this->fromCamel($data);
    }
}
