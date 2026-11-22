<?php
declare(strict_types=1);

namespace FlocpressBuilder\Frontend;

use FlocpressBuilder\Contracts\RegistrableInterface;

defined('ABSPATH') || exit;

final class FrontendAssets implements RegistrableInterface {

    public function __construct(private readonly BuilderContext $context) {}

    public function register(): void {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void {
        if (!is_singular()) {
            return;
        }

        if (!$this->context->is_builder_post((int) get_queried_object_id())) {
            return;
        }

        wp_enqueue_style('fpb-frontend', FPB_PLUGIN_URL . 'assets/css/frontend.css', [], FPB_VERSION);
    }
}