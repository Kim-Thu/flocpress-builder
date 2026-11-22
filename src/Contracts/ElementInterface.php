<?php
declare(strict_types=1);

namespace FlocpressBuilder\Contracts;

use FlocpressBuilder\Support\Css\Layer;

defined('ABSPATH') || exit;

interface ElementInterface {
    public function get_name(): string;
    public function get_layer(): Layer;
}