<?php
declare(strict_types=1);

namespace FlocpressBuilder\Support\Css;

defined('ABSPATH') || exit;

enum Layer: string {
    case Layout    = 'l';
    case Component = 'c';
    case Project   = 'p';
    case Utility   = 'u';
}