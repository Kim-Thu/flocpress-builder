<?php
declare(strict_types=1);

namespace FlocpressBuilder\Core;

use FlocpressBuilder\Contracts\EditorSchemaInterface;
use FlocpressBuilder\Contracts\ElementInterface;
use FlocpressBuilder\Contracts\ElementProviderInterface;

defined('ABSPATH') || exit;

final class ElementRegistry implements ElementProviderInterface {

    /** @var array<string, ElementInterface> */
    private array $elements = [];

    public function register(ElementInterface $element): void {
        $this->elements[$element->get_name()] = $element;
    }

    public function get(string $name): ?ElementInterface {
        return $this->elements[$name] ?? null;
    }

    /** @return array<int, array<string, mixed>> */
    public function editor_schemas(): array {
        $out = [];

        foreach ($this->elements as $element) {
            if (!$element instanceof EditorSchemaInterface) {
                continue;
            }

            $out[] = [
                'name'     => $element->get_name(),
                'layer'    => $element->get_layer()->value,
                'title'    => $element->get_title(),
                'icon'     => $element->get_icon(),
                'category' => $element->get_category(),
                'controls' => $element->get_controls(),
            ];
        }

        return $out;
    }
}