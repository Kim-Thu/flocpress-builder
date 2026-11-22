<?php
declare(strict_types=1);

namespace FlocpressBuilder\Sanitizer;

defined('ABSPATH') || exit;

final class SettingsSanitizer {

    /** @param iterable<ValueSanitizerInterface> $rules */
    public function __construct(private readonly iterable $rules) {}

    /**
     * @param array<string, mixed> $settings
     * @param array<string, array<string, mixed>> $controls
     * @return array<string, mixed>
     */
    public function sanitize(array $settings, array $controls): array {
        $clean = [];

        if ($controls === []) {
            foreach ($settings as $key => $value) {
                if (is_scalar($value)) {
                    $clean[sanitize_key((string) $key)] = sanitize_text_field((string) $value);
                }
            }
        } else {
            foreach ($controls as $key => $control) {
                $raw  = $settings[$key] ?? ($control['default'] ?? '');
                $type = (string) ($control['type'] ?? 'text');
                $clean[$key] = $this->apply($type, $raw, $control);
            }
        }

        $clean['_id']      = sanitize_key((string) ($settings['_id'] ?? ''));
        $clean['_classes'] = implode(' ', array_filter(array_map(
            'sanitize_html_class',
            explode(' ', (string) ($settings['_classes'] ?? ''))
        )));

        return $clean;
    }

    /** @param array<string, mixed> $control */
    private function apply(string $type, mixed $value, array $control): string {
        foreach ($this->rules as $rule) {
            if ($rule->supports($type)) {
                return $rule->sanitize($value, $control);
            }
        }

        return sanitize_text_field(is_scalar($value) ? (string) $value : '');
    }
}