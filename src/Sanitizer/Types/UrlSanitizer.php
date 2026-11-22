<?php
declare(strict_types=1);

namespace FlocpressBuilder\Sanitizer\Types;

use FlocpressBuilder\Sanitizer\ValueSanitizerInterface;

defined('ABSPATH') || exit;

final class UrlSanitizer implements ValueSanitizerInterface {

    public function supports(string $type): bool {
        return in_array($type, ['url', 'media'], true);
    }

    public function sanitize(mixed $value, array $control): string {
        return esc_url_raw(is_scalar($value) ? (string) $value : '');
    }
}