<?php
declare(strict_types=1);

defined('WP_UNINSTALL_PLUGIN') || exit;

global $wpdb;

$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('_fpb_page_data', '_fpb_is_active')");

wp_cache_flush();