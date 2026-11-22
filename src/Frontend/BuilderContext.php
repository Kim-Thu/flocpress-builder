<?php
declare(strict_types=1);

namespace FlocpressBuilder\Frontend;

use FlocpressBuilder\Persistence\PageDataRepository;

defined('ABSPATH') || exit;

final class BuilderContext {

    public function __construct(private readonly PageDataRepository $repository) {}

    public function is_builder_post(int $post_id): bool {
        if ($post_id <= 0 || post_password_required($post_id)) {
            return false;
        }

        return $this->repository->is_active($post_id);
    }
}