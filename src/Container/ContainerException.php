<?php
declare(strict_types=1);

namespace FlocpressBuilder\Container;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

defined('ABSPATH') || exit;

final class ContainerException extends RuntimeException implements ContainerExceptionInterface {}