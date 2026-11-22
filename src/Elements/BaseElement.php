<?php
declare(strict_types=1);

namespace FlocpressBuilder\Elements;

use FlocpressBuilder\Contracts\EditorSchemaInterface;
use FlocpressBuilder\Contracts\ElementInterface;
use FlocpressBuilder\Contracts\RenderableInterface;
use FlocpressBuilder\Contracts\StyleableInterface;
use FlocpressBuilder\Support\Css\ClassNames;
use FlocpressBuilder\Support\Css\Layer;
use FlocpressBuilder\Support\Css\StyleTokens;

defined('ABSPATH') || exit;

abstract class BaseElement implements
    ElementInterface,
    EditorSchemaInterface,
    RenderableInterface,
    StyleableInterface {

    public function get_layer(): Layer {
        return Layer::Component;
    }

    public function get_icon(): string {
        return 'dashicons-admin-generic';
    }

    public function get_category(): string {
        return 'basic';
    }

    final public function render(array $settings): string {
        $names  = $this->classnames($settings);
        $inner  = $this->content($settings, $names);

        if ($inner === '') {
            return '';
        }

        return $this->wrap($inner, $settings, $names, $this->tokens($settings));
    }

    abstract protected function content(array $settings, ClassNames $names): string;

    public function tokens(array $settings): StyleTokens {
        return StyleTokens::for($this->get_layer(), $this->get_name());
    }

    protected function classnames(array $settings): ClassNames {
        return ClassNames::make($this->get_layer(), $this->get_name())
            ->merge($settings['_classes'] ?? null);
    }

    private function wrap(string $inner, array $settings, ClassNames $names, StyleTokens $tokens): string {
        $attrs = sprintf(
            ' class="%s" data-element-id="%s" data-element-type="%s"',
            esc_attr((string) $names),
            esc_attr((string) ($settings['_id'] ?? '')),
            esc_attr($this->get_name())
        );

        if (!$tokens->isEmpty()) {
            $attrs .= sprintf(' style="%s"', esc_attr((string) $tokens));
        }

        return '<div' . $attrs . '>' . $inner . '</div>';
    }
}