<?php
declare(strict_types=1);

namespace FlocpressBuilder\Tests;

use FlocpressBuilder\Container\Container;
use FlocpressBuilder\Container\ContainerException;
use FlocpressBuilder\Container\NotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ContainerTest extends TestCase {

    public function testImplementsPsr11(): void {
        self::assertInstanceOf(ContainerInterface::class, new Container());
    }

    public function testSingletonReturnsSameInstance(): void {
        $c = new Container();
        $c->singleton(\stdClass::class);
        self::assertSame($c->get(\stdClass::class), $c->get(\stdClass::class));
    }

    public function testBindReturnsNewInstance(): void {
        $c = new Container();
        $c->bind(\stdClass::class);
        self::assertNotSame($c->get(\stdClass::class), $c->get(\stdClass::class));
    }

    public function testNotFoundThrowsPsrException(): void {
        $this->expectException(NotFoundException::class);
        (new Container())->get('Khong\\Ton\\Tai');
    }

    public function testCircularDependencyThrows(): void {
        $c = new Container();
        $c->singleton('loop', static fn (ContainerInterface $app) => $app->get('loop'));
        $this->expectException(ContainerException::class);
        $c->get('loop');
    }

    public function testAutowireResolvesTypedDependency(): void {
        $c = new Container();
        self::assertInstanceOf(NeedsStdClass::class, $c->get(NeedsStdClass::class));
    }
}

final class NeedsStdClass {
    public function __construct(public readonly \stdClass $dep) {}
}