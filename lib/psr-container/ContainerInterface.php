<?php
declare(strict_types=1);

namespace Psr\Container;

if (!interface_exists(ContainerInterface::class, false)) {
    interface ContainerInterface {
        public function get(string $id): mixed;
        public function has(string $id): bool;
    }
}