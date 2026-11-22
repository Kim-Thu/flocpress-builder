<?php
declare(strict_types=1);

namespace FlocpressBuilder\Contracts;

use FlocpressBuilder\Support\Css\StyleTokens;

defined('ABSPATH') || exit;

interface StyleableInterface {
    public function tokens(array $settings): StyleTokens;
}