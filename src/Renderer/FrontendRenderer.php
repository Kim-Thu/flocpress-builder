<?php
declare(strict_types=1);

namespace FlocpressBuilder\Renderer;

use FlocpressBuilder\Contracts\EditorSchemaInterface;
use FlocpressBuilder\Contracts\ElementProviderInterface;
use FlocpressBuilder\Contracts\ParentElementInterface;
use FlocpressBuilder\Contracts\RenderableInterface;
use FlocpressBuilder\Sanitizer\SettingsSanitizer;

defined('ABSPATH') || exit;

final class FrontendRenderer {

    public function __construct(
        private readonly ElementProviderInterface $provider,
        private readonly SettingsSanitizer $sanitizer
    ) {}

    public function render_page(array $nodes): string {
        return $this->render_nodes($nodes, 'n');
    }

    private function render_nodes(array $nodes, string $path): string {
        $html = '';

        foreach (array_values($nodes) as $index => $node) {
            $html .= $this->render_node($node, $path . $index);
        }

        return $html;
    }

    private function render_node(mixed $node, string $path): string {
        if (!is_array($node)) {
            return '';
        }

        $element = $this->provider->get(sanitize_key((string) ($node['type'] ?? '')));

        if (!$element instanceof RenderableInterface) {
            return '';
        }

        $controls = $element instanceof EditorSchemaInterface ? $element->get_controls() : [];
        $settings = $this->sanitizer->sanitize((array) ($node['settings'] ?? []), $controls);

        if (($settings['_id'] ?? '') === '') {
            $settings['_id'] = $path;
        }

        if ($element instanceof ParentElementInterface) {
            $settings['_children'] = $this->render_nodes((array) ($node['children'] ?? []), $path . '-');
        }

        return $element->render($settings);
    }
}