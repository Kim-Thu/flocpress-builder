<?php
declare(strict_types=1);

namespace FlocpressBuilder\Contracts;

defined('ABSPATH') || exit;

interface ElementProviderInterface {
    public function get(string $name): ?ElementInterface;
}