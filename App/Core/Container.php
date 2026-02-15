<?php
declare(strict_types=1);

namespace Core;

use ReflectionClass;
use Exception;

class Container
{
    protected array $instances = [];

    public function set(string $key, $value): void
    {
        $this->instances[$key] = $value;
    }

    public function get(string $key)
    {
        if (! isset($this->instances[$key])) {
            $this->set($key, $this->resolve($key));
        }

        return $this->instances[$key];
    }

    public function resolve(string $key)
    {
        $reflectionClass = new ReflectionClass($key);

        if (! $reflectionClass->isInstantiable()) {
            throw new Exception("Class {$key} is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (! $constructor) {
            return new $key;
        }

        $parameters = $constructor->getParameters();

        if (! $parameters) {
            return new $key;
        }

        $dependencies = array_map(function ($parameter) {
            $type = $parameter->getType();

            if (! $type) {
                throw new Exception("Failed to resolve class using Container, because parameter {$parameter->name} is missing a type hint.");
            }

            if ($type->isBuiltin()) {
                throw new Exception("Failed to resolve class using Container, because parameter {$parameter->name} is a built-in type.");
            }

            return $this->get($type->getName());

        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
