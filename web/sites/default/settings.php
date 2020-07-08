<?php

// @codingStandardsIgnoreFile

/**
 * @file
 * Drupal site-specific configuration file.
 */

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(DRUPAL_ROOT. '/..');
$dotenv->load();

$databases = [];
$databases['default']['default'] = [
  'database' => getenv('MYSQL_DATABASE'),
  'username' => getenv('MYSQL_USER'),
  'password' => getenv('MYSQL_PASSWORD'),
  'prefix' => '',
  'host' => getenv('MYSQL_HOSTNAME'),
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
];

$config['file.settings']['make_unused_managed_files_temporary'] = TRUE;
$config['system.performance']['fast_404']['exclude_paths'] = '/\/(?:styles)|(?:system\/files)\//';
$config['system.performance']['fast_404']['paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$config['system.performance']['fast_404']['html'] = '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

$settings['config_sync_directory'] = DRUPAL_ROOT . '/../config/sync';

$settings['hash_salt'] = getenv('HASH_SALT');
$settings['update_free_access'] = FALSE;
$settings['allow_authorize_operations'] = FALSE;
$settings['file_chmod_directory'] = 0775;
$settings['file_chmod_file'] = 0664;
$settings['file_temp_path'] = '/tmp';
$settings['maintenance_theme'] = 'bartik';
$settings['trusted_host_patterns'] = explode(',', str_replace([' ', '"'], '', getenv('HOSTS')));

$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
  'vendor',
];

$settings['entity_update_batch_size'] = 50;
$settings['entity_update_backup'] = TRUE;
$settings['migrate_node_migrate_type_classic'] = FALSE;

if (getenv('DEVELOPMENT_SETTINGS')) {
  include DRUPAL_ROOT . '/' . $site_path . '/settings.development.php';
}
else {
  $settings['container_yamls'][] = DRUPAL_ROOT . '/' . $site_path . '/services.yml';
}
