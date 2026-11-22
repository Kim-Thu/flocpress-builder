<?php
declare(strict_types=1);

namespace FlocpressBuilder\Tests;

use FlocpressBuilder\Support\Css\ClassNames;
use FlocpressBuilder\Support\Css\Layer;
use PHPUnit\Framework\TestCase;

final class ClassNamesTest extends TestCase {

    public function testBlockUsesFlocssLayerPrefix(): void {
        self::assertSame('c-heading', (string) ClassNames::make(Layer::Component, 'heading'));
        self::assertSame('l-row', (string) ClassNames::make(Layer::Layout, 'row'));
    }

    public function testElementAndModifier(): void {
        $names = ClassNames::make(Layer::Component, 'button')->modifier('outline');
        self::assertSame('c-button__label', $names->element('label'));
        self::assertSame('c-button c-button--outline', (string) $names);
    }

    public function testStateAndUtility(): void {
        $names = ClassNames::make(Layer::Component, 'button')->state('active')->utilities('mt-0');
        self::assertSame('c-button is-active u-mt-0', (string) $names);
    }

    public function testImmutable(): void {
        $base = ClassNames::make(Layer::Component, 'button');
        $base->modifier('outline');
        self::assertSame('c-button', (string) $base);
    }

    public function testInvalidNameThrows(): void {
        $this->expectException(\InvalidArgumentException::class);
        ClassNames::make(Layer::Component, '!!!');
    }
}