<?php
declare(strict_types=1);

namespace FlocpressBuilder\Sanitizer;

defined('ABSPATH') || exit;

/** Chuan hoa cay node khi LUU: gioi han do sau, so node, lam sach chuoi. */
final class PageDataSanitizer {

    private const MAX_DEPTH = 8;
    private const MAX_NODES = 500;

    private int $count = 0;

    public function sanitize(mixed $raw): array {
        $this->count = 0;

        return $this->nodes(is_array($raw) ? $raw : [], 1);
    }

    private function nodes(array $raw, int $depth): array {
        if ($depth > self::MAX_DEPTH) {
            return [];
        }

        $out = [];

        foreach ($raw as $node) {
            if (!is_array($node) || $this->count >= self::MAX_NODES) {
                continue;
            }

            $type = sanitize_key((string) ($node['type'] ?? ''));

            if ($type === '') {
                continue;
            }

            $this->count++;

            $out[] = [
                'type'     => $type,
                'settings' => $this->settings(is_array($node['settings'] ?? null) ? $node['settings'] : []),
                'children' => $this->nodes(is_array($node['children'] ?? null) ? $node['children'] : [], $depth + 1),
            ];
        }

        return $out;
    }

    private function settings(array $raw): array {
        $out = [];

        foreach ($raw as $key => $value) {
            $clean_key = sanitize_key((string) $key);

            if ($clean_key === '' || !is_scalar($value)) {
                continue;
            }

            $out[$clean_key] = wp_kses_post((string) $value);
        }

        return $out;
    }
}