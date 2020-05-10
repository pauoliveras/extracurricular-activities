<?php

namespace App\Tests\Infrastructure\Service;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class ReflectionEntityManager
{
    public static function create(): self
    {
        return new self();
    }

    public function buildObject(string $className, array $params = [])
    {
        $reflectionClass = new ReflectionClass($className);
        $object = $reflectionClass->newInstanceWithoutConstructor();
        $this->setPropertyValues($params, $object);

        return $object;
    }

    private function setPropertyValues(array $params, $object): void
    {
        foreach ($params as $paramName => $paramValue) {
            $this->setPropertyValue($object, $paramName, $paramValue);
        }
    }

    public function setPropertyValue($object, $propertyName, $value): void
    {
        $reflectionClass = new ReflectionClass(get_class($object));
        $reflectionProperty = $this->getPropertyConsideringParentClasses($reflectionClass, $object, $propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
        $reflectionProperty->setAccessible(false);
    }

    private function getPropertyConsideringParentClasses(ReflectionClass $reflectionClass, $object, $propertyName): ReflectionProperty
    {
        try {
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
        } catch (ReflectionException $e) {
            if (!get_parent_class($object)) {
                throw $e;
            }
            $reflectionClass = new ReflectionClass(get_parent_class($object));
            $reflectionProperty = $this->getPropertyConsideringParentClasses($reflectionClass, get_parent_class($object), $propertyName);
        }

        return $reflectionProperty;
    }

    public function getPropertyValue($object, $propertyName)
    {
        $reflectionClass = new ReflectionClass(get_class($object));
        $reflectionProperty = $this->getPropertyConsideringParentClasses($reflectionClass, $object, $propertyName);
        $reflectionProperty->setAccessible(true);

        $value = $reflectionProperty->getValue($object);

        $reflectionProperty->setAccessible(false);

        return $value;
    }
}