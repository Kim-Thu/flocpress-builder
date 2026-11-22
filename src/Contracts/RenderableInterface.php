<?php
declare(strict_types=1);

namespace FlocpressBuilder\Contracts;

defined('ABSPATH') || exit;

interface RenderableInterface {
    public function render(array $settings): string;
}