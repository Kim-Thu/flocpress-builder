<?php
declare(strict_types=1);

namespace FlocpressBuilder\Core;

use FlocpressBuilder\Admin\EditorAssets;
use FlocpressBuilder\Admin\EditorMetaBox;
use FlocpressBuilder\Contracts\RegistrableInterface;
use FlocpressBuilder\Editor\EditorController;
use FlocpressBuilder\Frontend\ContentFilter;
use FlocpressBuilder\Frontend\FrontendAssets;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final class Plugin {

    private const SERVICES = [
        ServiceBindings::class,
        DefaultElements::class,
        FrontendAssets::class,
        ContentFilter::class,
        EditorMetaBox::class,
        EditorAssets::class,
        EditorController::class,
    ];

    public function __construct(private readonly ContainerInterface $container) {}

    public function boot(): void {
        foreach (self::SERVICES as $id) {
            $service = $this->container->get($id);

            if ($service instanceof RegistrableInterface) {
                $service->register();
            }
        }
    }
}