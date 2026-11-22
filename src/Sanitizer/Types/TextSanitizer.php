<?php
declare(strict_types=1);

namespace FlocpressBuilder\Sanitizer\Types;

use FlocpressBuilder\Sanitizer\ValueSanitizerInterface;

defined('ABSPATH') || exit;

final class TextSanitizer implements ValueSanitizerInterface {

    public function supports(string $type): bool {
        return in_array($type, ['text', 'textarea'], true);
    }

    public function sanitize(mixed $value, array $control): string {
        return sanitize_text_field(is_scalar($value) ? (string) $value : '');
    }
}