<?php
declare(strict_types=1);

namespace FlocpressBuilder\Core;

use FlocpressBuilder\Container\RegistrableContainer;
use FlocpressBuilder\Contracts\ElementProviderInterface;
use FlocpressBuilder\Contracts\RegistrableInterface;
use FlocpressBuilder\Sanitizer\SettingsSanitizer;
use FlocpressBuilder\Sanitizer\Types\ColorSanitizer;
use FlocpressBuilder\Sanitizer\Types\NumberSanitizer;
use FlocpressBuilder\Sanitizer\Types\SelectSanitizer;
use FlocpressBuilder\Sanitizer\Types\TextSanitizer;
use FlocpressBuilder\Sanitizer\Types\UrlSanitizer;

defined('ABSPATH') || exit;

final class ServiceBindings implements RegistrableInterface {

    public function __construct(private readonly RegistrableContainer $container) {}

    public function register(): void {
        $this->container->singleton(ElementRegistry::class);
        $this->container->singleton(ElementProviderInterface::class, ElementRegistry::class);

        $this->container->singleton(SettingsSanitizer::class, static fn (): SettingsSanitizer => new SettingsSanitizer([
            new TextSanitizer(),
            new ColorSanitizer(),
            new UrlSanitizer(),
            new SelectSanitizer(),
            new NumberSanitizer(),
        ]));
    }
}