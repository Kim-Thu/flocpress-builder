<?php
declare(strict_types=1);

namespace Psr\Container;

if (!interface_exists(ContainerExceptionInterface::class, false)) {
    interface ContainerExceptionInterface extends \Throwable {}
}