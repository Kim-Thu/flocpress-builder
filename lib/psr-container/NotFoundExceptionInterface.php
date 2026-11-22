<?php
declare(strict_types=1);

namespace Psr\Container;

if (!interface_exists(NotFoundExceptionInterface::class, false)) {
    interface NotFoundExceptionInterface extends ContainerExceptionInterface {}
}