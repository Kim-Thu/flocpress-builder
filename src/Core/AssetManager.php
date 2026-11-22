<?php
namespace FlocpressBuilder\Core;

class AssetManager {
    public function register(): void {
        // Frontend assets
        wp_register_style(
            'fpb-frontend',
            FPB_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            FPB_VERSION
        );

        // Editor assets
        wp_register_style(
            'fpb-editor',
            FPB_PLUGIN_URL . 'assets/css/editor.css',
            [],
            FPB_VERSION
        );

        wp_register_script(
            'fpb-editor',
            FPB_PLUGIN_URL . 'assets/js/editor.js',
            ['wp-element', 'wp-components', 'wp-api-fetch'],
            FPB_VERSION,
            true
        );

        wp_localize_script('fpb-editor', 'fpbEditorConfig', [
            'restUrl'  => rest_url('flocpress-builder/v1/'),
            'nonce'    => wp_create_nonce('wp_rest'),
            'postId'   => get_the_ID(),
            'elements' => [], // Will be injected by Plugin
        ]);
    }

    public function enqueue_frontend(): void {
        wp_enqueue_style('fpb-frontend');
    }

    public function enqueue_editor(): void {
        wp_enqueue_style('fpb-editor');
        wp_enqueue_script('fpb-editor');
    }
}