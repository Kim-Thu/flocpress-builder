<?php
declare(strict_types=1);

namespace FlocpressBuilder\Contracts;

defined('ABSPATH') || exit;

interface RegistrableInterface {
    public function register(): void;
}