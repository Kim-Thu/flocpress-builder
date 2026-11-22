<?php
declare(strict_types=1);

namespace FlocpressBuilder\Support\Css;

use Stringable;

defined('ABSPATH') || exit;

final class StyleTokens implements Stringable {

    /** @var array<string, string> */
    private array $tokens = [];

    private function __construct(private readonly string $scope) {}

    public static function for(Layer $layer, string $name): self {
        return new self($layer->value . '-' . $name);
    }

    public function set(string $property, string|int|float|null $value): self {
        if ($value === null || $value === '') {
            return $this;
        }

        $prop = trim(preg_replace('/[^a-z0-9-]+/', '-', strtolower($property)) ?? '', '-');

        if ($prop === '') {
            return $this;
        }

        $this->tokens['--' . $this->scope . '-' . $prop] = self::guard((string) $value);

        return $this;
    }

    public function isEmpty(): bool {
        return $this->tokens === [];
    }

    public function __toString(): string {
        $out = '';

        foreach ($this->tokens as $key => $value) {
            $out .= $key . ':' . $value . ';';
        }

        return $out;
    }

    private static function guard(string $value): string {
        $value = trim($value);

        if ($value === '' || preg_match('/[;{}<>\\\\]|expression|javascript:|@import|behavior:/i', $value)) {
            return 'initial';
        }

        return $value;
    }
}