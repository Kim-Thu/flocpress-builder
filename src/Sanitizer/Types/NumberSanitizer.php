<?php
declare(strict_types=1);

namespace FlocpressBuilder\Sanitizer\Types;

use FlocpressBuilder\Sanitizer\ValueSanitizerInterface;

defined('ABSPATH') || exit;

final class NumberSanitizer implements ValueSanitizerInterface {

    public function supports(string $type): bool {
        return in_array($type, ['number', 'range'], true);
    }

    public function sanitize(mixed $value, array $control): string {
        if (!is_numeric($value)) {
            return (string) ($control['default'] ?? '0');
        }

        $number = (float) $value;
        $min    = isset($control['min']) && is_numeric($control['min']) ? (float) $control['min'] : null;
        $max    = isset($control['max']) && is_numeric($control['max']) ? (float) $control['max'] : null;

        if ($min !== null) { $number = max($min, $number); }
        if ($max !== null) { $number = min($max, $number); }

        return (string) $number;
    }
}