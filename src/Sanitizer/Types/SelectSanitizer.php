<?php
declare(strict_types=1);

namespace FlocpressBuilder\Sanitizer\Types;

use FlocpressBuilder\Sanitizer\ValueSanitizerInterface;

defined('ABSPATH') || exit;

final class SelectSanitizer implements ValueSanitizerInterface {

    public function supports(string $type): bool {
        return $type === 'select';
    }

    public function sanitize(mixed $value, array $control): string {
        $value   = sanitize_key(is_scalar($value) ? (string) $value : '');
        $options = array_map('sanitize_key', array_map('strval', (array) ($control['options'] ?? [])));

        if (in_array($value, $options, true)) {
            return $value;
        }

        return sanitize_key((string) ($control['default'] ?? ''));
    }
}