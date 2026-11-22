<?php
declare(strict_types=1);

namespace FlocpressBuilder\Elements;

use FlocpressBuilder\Contracts\ParentElementInterface;
use FlocpressBuilder\Support\Css\ClassNames;
use FlocpressBuilder\Support\Css\Layer;
use FlocpressBuilder\Support\Css\StyleTokens;

defined('ABSPATH') || exit;

final class ColumnElement extends BaseElement implements ParentElementInterface {

    public function get_name(): string     { return 'col'; }
    public function get_title(): string    { return __('Column', 'flocpress-builder'); }
    public function get_icon(): string     { return 'dashicons-screenoptions'; }
    public function get_layer(): Layer     { return Layer::Layout; }
    public function get_category(): string { return 'layout'; }

    public function get_controls(): array {
        return [
            'basis' => ['type' => 'text', 'default' => '', 'label' => __('Basis (vd 50%)', 'flocpress-builder')],
        ];
    }

    public function tokens(array $settings): StyleTokens {
        return parent::tokens($settings)->set('basis', $settings['basis'] ?? null);
    }

    protected function content(array $settings, ClassNames $names): string {
        return (string) ($settings['_children'] ?? '');
    }
}