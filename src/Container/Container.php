<?php
declare(strict_types=1);

namespace FlocpressBuilder\Container;

use Closure;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use Throwable;

defined('ABSPATH') || exit;

final class Container implements RegistrableContainer {

    /** @var array<string, array{concrete: Closure|string, shared: bool}> */
    private array $bindings = [];

    /** @var array<string, object> */
    private array $instances = [];

    /** @var array<string, true> */
    private array $resolving = [];

    public function bind(string $id, Closure|string|null $concrete = null, bool $shared = false): void {
        $this->bindings[$id] = ['concrete' => $concrete ?? $id, 'shared' => $shared];
    }

    public function singleton(string $id, Closure|string|null $concrete = null): void {
        $this->bind($id, $concrete, true);
    }

    public function instance(string $id, object $instance): void {
        $this->instances[$id] = $instance;
    }

    public function get(string $id): mixed {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->resolving[$id])) {
            throw new ContainerException("Circular dependency detected: {$id}");
        }

        if (!$this->has($id)) {
            throw new NotFoundException("Service not found: {$id}");
        }

        $this->resolving[$id] = true;

        try {
            $concrete = $this->bindings[$id]['concrete'] ?? $id;
            $object   = $concrete instanceof Closure
                ? $concrete($this)
                : $this->autowire((string) $concrete);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ContainerException("Cannot resolve {$id}: " . $e->getMessage(), 0, $e);
        } finally {
            unset($this->resolving[$id]);
        }

        if (!is_object($object)) {
            throw new ContainerException("Service {$id} must resolve to an object.");
        }

        if ($this->bindings[$id]['shared'] ?? false) {
            $this->instances[$id] = $object;
        }

        return $object;
    }

    public function has(string $id): bool {
        if (isset($this->instances[$id]) || isset($this->bindings[$id])) {
            return true;
        }

        if (!class_exists($id)) {
            return false;
        }

        try {
            return (new ReflectionClass($id))->isInstantiable();
        } catch (ReflectionException) {
            return false;
        }
    }

    private function autowire(string $class): object {
        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new NotFoundException("Class {$class} does not exist.", 0, $e);
        }

        if (!$reflection->isInstantiable()) {
            throw new ContainerException("Class {$class} is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $args = [];

        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $args[] = $this->get($type->getName());
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }

            throw new ContainerException(
                "Cannot autowire parameter {$param->getName()} of {$class}: missing type hint or default value."
            );
        }

        return $reflection->newInstanceArgs($args);
    }
}