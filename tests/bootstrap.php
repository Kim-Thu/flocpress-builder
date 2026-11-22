<?php
declare(strict_types=1);

define('ABSPATH', __DIR__ . '/');

require_once dirname(__DIR__) . '/vendor/autoload.php';

if (!function_exists('sanitize_html_class')) {
    function sanitize_html_class(string $class, string $fallback = ''): string {
        $clean = preg_replace('/[^A-Za-z0-9_-]/', '', $class) ?? '';
        return $clean === '' ? $fallback : $clean;
    }
}

if (!function_exists('sanitize_key')) {
    function sanitize_key(string $key): string {
        return preg_replace('/[^a-z0-9_\-]/', '', strtolower($key)) ?? '';
    }
}