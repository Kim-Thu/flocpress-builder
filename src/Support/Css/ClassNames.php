<?php
declare(strict_types=1);

namespace FlocpressBuilder\Support\Css;

use InvalidArgumentException;
use Stringable;

defined('ABSPATH') || exit;

final class ClassNames implements Stringable {

    /** @var string[] */
    private array $classes;

    private function __construct(private readonly string $block, array $classes = []) {
        $this->classes = $classes === [] ? [$block] : $classes;
    }

    public static function make(Layer $layer, string $name): self {
        return new self($layer->value . '-' . self::slug($name));
    }

    public function block(): string {
        return $this->block;
    }

    public function element(string $name): string {
        return $this->block . '__' . self::slug($name);
    }

    public function modifier(string $name, bool $when = true): self {
        if (!$when) {
            return $this;
        }

        $clone = clone $this;
        $clone->classes[] = $this->block . '--' . self::slug($name);

        return $clone;
    }

    public function state(string $name, bool $when = true): self {
        if (!$when) {
            return $this;
        }

        $clone = clone $this;
        $clone->classes[] = 'is-' . self::slug($name);

        return $clone;
    }

    public function utilities(string ...$names): self {
        $clone = clone $this;

        foreach ($names as $name) {
            $clone->classes[] = 'u-' . self::slug($name);
        }

        return $clone;
    }

    public function merge(?string $raw): self {
        if ($raw === null || trim($raw) === '') {
            return $this;
        }

        $clone = clone $this;

        foreach (preg_split('/\s+/', trim($raw)) ?: [] as $token) {
            $clean = sanitize_html_class($token);
            if ($clean !== '') {
                $clone->classes[] = $clean;
            }
        }

        return $clone;
    }

    public function __toString(): string {
        return implode(' ', array_unique($this->classes));
    }

    private static function slug(string $name): string {
        $slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($name))) ?? '', '-');

        if ($slug === '') {
            throw new InvalidArgumentException("Invalid FLOCSS name: {$name}");
        }

        return $slug;
    }
}