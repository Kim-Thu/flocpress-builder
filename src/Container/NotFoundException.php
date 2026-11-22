<?php
declare(strict_types=1);

namespace FlocpressBuilder\Container;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

defined('ABSPATH') || exit;

final class NotFoundException extends RuntimeException implements NotFoundExceptionInterface {}