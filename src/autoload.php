<?php
declare(strict_types=1);

defined('ABSPATH') || exit;

spl_autoload_register(static function (string $class): void {
    $map = [
        'FlocpressBuilder\\' => __DIR__ . '/',
        'Psr\\Container\\'   => dirname(__DIR__) . '/lib/psr-container/',
    ];

    foreach ($map as $prefix => $base) {
        if (!str_starts_with($class, $prefix)) {
            continue;
        }

        $file = $base . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

        if (is_readable($file)) {
            require_once $file;
        }

        return;
    }
});