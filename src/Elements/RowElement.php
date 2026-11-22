<?php
declare(strict_types=1);

namespace FlocpressBuilder\Elements;

use FlocpressBuilder\Contracts\ParentElementInterface;
use FlocpressBuilder\Support\Css\ClassNames;
use FlocpressBuilder\Support\Css\Layer;
use FlocpressBuilder\Support\Css\StyleTokens;

defined('ABSPATH') || exit;

final class RowElement extends BaseElement implements ParentElementInterface {

    public function get_name(): string     { return 'row'; }
    public function get_title(): string    { return __('Row', 'flocpress-builder'); }
    public function get_icon(): string     { return 'dashicons-columns'; }
    public function get_layer(): Layer     { return Layer::Layout; }
    public function get_category(): string { return 'layout'; }

    public function get_controls(): array {
        return [
            'gap'   => ['type' => 'text',   'default' => '', 'label' => __('Gap', 'flocpress-builder')],
            'align' => ['type' => 'select', 'default' => 'stretch', 'options' => ['start','center','end','stretch'], 'label' => __('Align items', 'flocpress-builder')],
        ];
    }

    protected function classnames(array $settings): ClassNames {
        return parent::classnames($settings)->modifier('align-' . ($settings['align'] ?? 'stretch'));
    }

    public function tokens(array $settings): StyleTokens {
        return parent::tokens($settings)->set('gap', $settings['gap'] ?? null);
    }

    protected function content(array $settings, ClassNames $names): string {
        return (string) ($settings['_children'] ?? '');
    }
}