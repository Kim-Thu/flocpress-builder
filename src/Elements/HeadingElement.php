<?php
declare(strict_types=1);

namespace FlocpressBuilder\Elements;

use FlocpressBuilder\Support\Css\ClassNames;
use FlocpressBuilder\Support\Css\StyleTokens;

defined('ABSPATH') || exit;

final class HeadingElement extends BaseElement {

    public function get_name(): string  { return 'heading'; }
    public function get_title(): string { return __('Heading', 'flocpress-builder'); }
    public function get_icon(): string  { return 'dashicons-editor-heading'; }

    public function get_controls(): array {
        return [
            'text'      => ['type' => 'text',   'default' => 'Tieu de', 'label' => __('Text', 'flocpress-builder')],
            'tag'       => ['type' => 'select', 'default' => 'h2', 'options' => ['h1','h2','h3','h4','h5','h6'], 'label' => __('Tag', 'flocpress-builder')],
            'align'     => ['type' => 'select', 'default' => 'left', 'options' => ['left','center','right'], 'label' => __('Align', 'flocpress-builder')],
            'color'     => ['type' => 'color',  'default' => '', 'label' => __('Color', 'flocpress-builder')],
            'font_size' => ['type' => 'text',   'default' => '', 'label' => __('Font size', 'flocpress-builder')],
        ];
    }

    protected function classnames(array $settings): ClassNames {
        return parent::classnames($settings)->modifier('align-' . ($settings['align'] ?? 'left'));
    }

    public function tokens(array $settings): StyleTokens {
        return parent::tokens($settings)
            ->set('color', $settings['color'] ?? null)
            ->set('font-size', $settings['font_size'] ?? null);
    }

    protected function content(array $settings, ClassNames $names): string {
        $allowed = ['h1','h2','h3','h4','h5','h6'];
        $tag     = in_array($settings['tag'] ?? 'h2', $allowed, true) ? $settings['tag'] : 'h2';

        return sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            $tag,
            esc_attr($names->element('text')),
            esc_html($settings['text'] ?? '')
        );
    }
}