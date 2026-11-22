<?php
declare(strict_types=1);

namespace FlocpressBuilder\Elements;

use FlocpressBuilder\Support\Css\ClassNames;
use FlocpressBuilder\Support\Css\StyleTokens;

defined('ABSPATH') || exit;

final class ButtonElement extends BaseElement {

    public function get_name(): string  { return 'button'; }
    public function get_title(): string { return __('Button', 'flocpress-builder'); }
    public function get_icon(): string  { return 'dashicons-button'; }

    public function get_controls(): array {
        return [
            'text'       => ['type' => 'text',   'default' => 'Click me', 'label' => __('Text', 'flocpress-builder')],
            'url'        => ['type' => 'url',    'default' => '#', 'label' => __('Link', 'flocpress-builder')],
            'variant'    => ['type' => 'select', 'default' => 'solid', 'options' => ['solid','outline','ghost'], 'label' => __('Variant', 'flocpress-builder')],
            'size'       => ['type' => 'select', 'default' => 'medium', 'options' => ['small','medium','large'], 'label' => __('Size', 'flocpress-builder')],
            'background' => ['type' => 'color',  'default' => '', 'label' => __('Background', 'flocpress-builder')],
            'text_color' => ['type' => 'color',  'default' => '', 'label' => __('Text color', 'flocpress-builder')],
            'radius'     => ['type' => 'text',   'default' => '', 'label' => __('Radius', 'flocpress-builder')],
        ];
    }

    protected function classnames(array $settings): ClassNames {
        return parent::classnames($settings)
            ->modifier($settings['variant'] ?? 'solid')
            ->modifier('size-' . ($settings['size'] ?? 'medium'));
    }

    public function tokens(array $settings): StyleTokens {
        return parent::tokens($settings)
            ->set('background', $settings['background'] ?? null)
            ->set('color', $settings['text_color'] ?? null)
            ->set('radius', $settings['radius'] ?? null);
    }

    protected function content(array $settings, ClassNames $names): string {
        return sprintf(
            '<a class="%1$s" href="%2$s"><span class="%3$s">%4$s</span></a>',
            esc_attr($names->element('link')),
            esc_url($settings['url'] ?? '#'),
            esc_attr($names->element('label')),
            esc_html($settings['text'] ?? '')
        );
    }
}