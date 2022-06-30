<?php

declare(strict_types=1);

namespace VueManager\Models;

use ReflectionClass;
use ReflectionProperty;

abstract class AbstractModel
{
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
        return $params;
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
    public function __toArray(): array
    {
        return evolutionCMS()->db->escape(get_object_vars($this));
    }
}
