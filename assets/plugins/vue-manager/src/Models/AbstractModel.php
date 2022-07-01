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
     * @var bool
     */
    protected bool $__saved = false;

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
            }

            if (is_callable([$this, $setter])) {
                $this->$setter($reflectionProperty->getValue($this));
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
        $this->__saved = true;

        return evolutionCMS()->db->escape($this->__toArray());
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return $this->fromCamel(get_object_vars($this));
    }
}
