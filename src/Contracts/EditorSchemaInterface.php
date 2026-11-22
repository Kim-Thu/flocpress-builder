<?php
declare(strict_types=1);

namespace FlocpressBuilder\Contracts;

defined('ABSPATH') || exit;

interface EditorSchemaInterface {
    public function get_title(): string;
    public function get_icon(): string;
    public function get_category(): string;

    /** @return array<string, array<string, mixed>> */
    public function get_controls(): array;
}