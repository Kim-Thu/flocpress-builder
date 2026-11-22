<?php
declare(strict_types=1);

namespace FlocpressBuilder\Persistence;

defined('ABSPATH') || exit;

/** Noi duy nhat doc/ghi post meta cua builder. */
final class PageDataRepository {

    private const DATA_KEY   = '_fpb_page_data';
    private const ACTIVE_KEY = '_fpb_is_active';

    public function is_active(int $post_id): bool {
        return (bool) get_post_meta($post_id, self::ACTIVE_KEY, true);
    }

    /** @return array<int, array<string, mixed>> */
    public function get(int $post_id): array {
        $data = get_post_meta($post_id, self::DATA_KEY, true);

        return is_array($data) ? $data : [];
    }

    public function save(int $post_id, array $data): void {
        update_post_meta($post_id, self::DATA_KEY, $data);
        update_post_meta($post_id, self::ACTIVE_KEY, 1);
    }

    public function set_active(int $post_id, bool $active): void {
        if ($active) {
            update_post_meta($post_id, self::ACTIVE_KEY, 1);
            return;
        }

        delete_post_meta($post_id, self::ACTIVE_KEY);
    }
}