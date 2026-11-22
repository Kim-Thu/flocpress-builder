<?php
declare(strict_types=1);

namespace FlocpressBuilder\Sanitizer;

defined('ABSPATH') || exit;

interface ValueSanitizerInterface {
    public function supports(string $type): bool;

    /** @param array<string, mixed> $control */
    public function sanitize(mixed $value, array $control): string;
}