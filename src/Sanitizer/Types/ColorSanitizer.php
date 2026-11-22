<?php
declare(strict_types=1);

namespace FlocpressBuilder\Sanitizer\Types;

use FlocpressBuilder\Sanitizer\ValueSanitizerInterface;

defined('ABSPATH') || exit;

final class ColorSanitizer implements ValueSanitizerInterface {

    public function supports(string $type): bool {
        return $type === 'color';
    }

    public function sanitize(mixed $value, array $control): string {
        $value = trim(is_scalar($value) ? (string) $value : '');

        if (preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $value) === 1) {
            return $value;
        }

        if (preg_match('/^(rgb|rgba|hsl|hsla)\([0-9.,%\s\/]+\)$/', $value) === 1) {
            return $value;
        }

        return '';
    }
}