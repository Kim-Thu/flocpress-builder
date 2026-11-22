<?php
declare(strict_types=1);

namespace FlocpressBuilder\Elements;

use FlocpressBuilder\Support\Css\ClassNames;
use FlocpressBuilder\Support\Css\StyleTokens;

defined('ABSPATH') || exit;

final class ImageElement extends BaseElement {

    public function get_name(): string  { return 'image'; }
    public function get_title(): string { return __('Image', 'flocpress-builder'); }
    public function get_icon(): string  { return 'dashicons-format-image'; }

    public function get_controls(): array {
        return [
            'src'    => ['type' => 'media', 'default' => '', 'label' => __('Image URL', 'flocpress-builder')],
            'alt'    => ['type' => 'text',  'default' => '', 'label' => __('Alt', 'flocpress-builder')],
            'width'  => ['type' => 'text',  'default' => '', 'label' => __('Width', 'flocpress-builder')],
            'radius' => ['type' => 'text',  'default' => '', 'label' => __('Radius', 'flocpress-builder')],
        ];
    }

    public function tokens(array $settings): StyleTokens {
        return parent::tokens($settings)
            ->set('width', $settings['width'] ?? null)
            ->set('radius', $settings['radius'] ?? null);
    }

    protected function content(array $settings, ClassNames $names): string {
        $src = esc_url($settings['src'] ?? '');

        if ($src === '') {
            return '';
        }

        return sprintf(
            '<img class="%1$s" src="%2$s" alt="%3$s" loading="lazy" decoding="async">',
            esc_attr($names->element('media')),
            $src,
            esc_attr($settings['alt'] ?? '')
        );
    }
}