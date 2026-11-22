<?php
declare(strict_types=1);

namespace FlocpressBuilder\Frontend;

use FlocpressBuilder\Contracts\RegistrableInterface;
use FlocpressBuilder\Persistence\PageDataRepository;
use FlocpressBuilder\Renderer\FrontendRenderer;
use WP_Post;

defined('ABSPATH') || exit;

final class ContentFilter implements RegistrableInterface {

    public function __construct(
        private readonly BuilderContext $context,
        private readonly PageDataRepository $repository,
        private readonly FrontendRenderer $renderer
    ) {}

    public function register(): void {
        add_filter('the_content', [$this, 'filter'], 20);
    }

    public function filter(string $content): string {
        $post = get_post();

        if (!$post instanceof WP_Post || !is_singular() || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        if (!$this->context->is_builder_post($post->ID)) {
            return $content;
        }

        $data = $this->repository->get($post->ID);

        return $data === [] ? $content : $this->renderer->render_page($data);
    }
}