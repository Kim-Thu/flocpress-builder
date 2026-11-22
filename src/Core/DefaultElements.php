<?php
declare(strict_types=1);

namespace FlocpressBuilder\Core;

use FlocpressBuilder\Contracts\RegistrableInterface;
use FlocpressBuilder\Elements\ButtonElement;
use FlocpressBuilder\Elements\ColumnElement;
use FlocpressBuilder\Elements\HeadingElement;
use FlocpressBuilder\Elements\ImageElement;
use FlocpressBuilder\Elements\RowElement;

defined('ABSPATH') || exit;

final class DefaultElements implements RegistrableInterface {

    public function __construct(private readonly ElementRegistry $registry) {}

    public function register(): void {
        $this->registry->register(new RowElement());
        $this->registry->register(new ColumnElement());
        $this->registry->register(new HeadingElement());
        $this->registry->register(new ButtonElement());
        $this->registry->register(new ImageElement());

        do_action('flocpress_builder_register_elements', $this->registry);
    }
}