<?php
/**
 * Plugin Name:       Flocpress Builder
 * Description:       Page builder theo SOLID, PSR-4, PSR-11, FLOCSS.
 * Version:           1.1.0
 * Author:            Lady
 * License:           GPL-2.0-or-later
 * Text Domain:       flocpress-builder
 * Requires at least: 6.0
 * Requires PHP:      8.1
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

define('FPB_VERSION', '1.1.0');
define('FPB_PLUGIN_FILE', __FILE__);
define('FPB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FPB_PLUGIN_URL', plugin_dir_url(__FILE__));

if (is_readable(FPB_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once FPB_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    require_once FPB_PLUGIN_DIR . 'src/autoload.php';
}

add_action('plugins_loaded', static function (): void {
    $container = new \FlocpressBuilder\Container\Container();
    $container->instance(\Psr\Container\ContainerInterface::class, $container);
    $container->instance(\FlocpressBuilder\Container\RegistrableContainer::class, $container);
    $container->get(\FlocpressBuilder\Core\Plugin::class)->boot();
});

add_action('init', static function (): void {
    load_plugin_textdomain('flocpress-builder', false, dirname(plugin_basename(FPB_PLUGIN_FILE)) . '/languages');
});

register_activation_hook(__FILE__, static function (): void { flush_rewrite_rules(); });
register_deactivation_hook(__FILE__, static function (): void { flush_rewrite_rules(); });