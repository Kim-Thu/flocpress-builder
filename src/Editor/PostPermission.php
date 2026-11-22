<?php
declare(strict_types=1);

namespace FlocpressBuilder\Editor;

use WP_Post;

defined('ABSPATH') || exit;

final class PostPermission {

    public function can_edit(int $post_id): bool {
        return $post_id > 0 && current_user_can('edit_post', $post_id);
    }

    public function can_read(int $post_id): bool {
        $post = get_post($post_id);

        if (!$post instanceof WP_Post) {
            return false;
        }

        return $post->post_status === 'publish' || current_user_can('edit_post', $post_id);
    }
}