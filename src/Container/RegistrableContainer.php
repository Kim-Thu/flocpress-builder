<?php
declare(strict_types=1);

namespace FlocpressBuilder\Container;

use Closure;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

interface RegistrableContainer extends ContainerInterface {
    public function bind(string $id, Closure|string|null $concrete = null, bool $shared = false): void;
    public function singleton(string $id, Closure|string|null $concrete = null): void;
    public function instance(string $id, object $instance): void;
}