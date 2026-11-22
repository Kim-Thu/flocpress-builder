<?php
declare(strict_types=1);

namespace FlocpressBuilder\Admin;

use FlocpressBuilder\Contracts\RegistrableInterface;
use FlocpressBuilder\Core\ElementRegistry;
use WP_Post;

defined('ABSPATH') || exit;

final class EditorAssets implements RegistrableInterface {

    public function __construct(private readonly ElementRegistry $registry) {}

    public function register(): void {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(string $hook): void {
        if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
            return;
        }

        $post = get_post();

        if (!$post instanceof WP_Post || !in_array($post->post_type, ['post', 'page'], true)) {
            return;
        }

        wp_enqueue_style('fpb-frontend', FPB_PLUGIN_URL . 'assets/css/frontend.css', [], FPB_VERSION);
        wp_enqueue_style('fpb-editor', FPB_PLUGIN_URL . 'assets/css/editor.css', ['fpb-frontend'], FPB_VERSION);

        wp_enqueue_script('fpb-editor', FPB_PLUGIN_URL . 'assets/js/editor.js', ['wp-api-fetch'], FPB_VERSION, true);

        wp_localize_script('fpb-editor', 'FlocpressEditor', [
            'restNamespace' => 'flocpress-builder/v1',
            'postId'        => $post->ID,
            'elements'      => $this->registry->editor_schemas(),
            'i18n'          => [
                'saved'  => __('Da luu', 'flocpress-builder'),
                'saving' => __('Dang luu...', 'flocpress-builder'),
                'error'  => __('Loi', 'flocpress-builder'),
            ],
        ]);
    }
}