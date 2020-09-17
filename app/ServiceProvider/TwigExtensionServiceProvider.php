<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Twig extension service provider class.
 */
class TwigExtensionServiceProvider {

  /**
   * Loads the block services.
   */
  public static function load(ContainerBuilder $container): void {
    self::base64($container);
    self::manifest($container);
  }

  /**
   * Base64 twig extension service.
   */
  private static function base64(ContainerBuilder $container): void {
    $container->register('app.twig.base64', 'App\Twig\Extension\Base64TwigExtension')
      ->addTag('twig.extension');
  }

  /**
   * Manifest twig extension service.
   */
  private static function manifest(ContainerBuilder $container): void {
    $container->register('app.twig.manifest', 'App\Twig\Extension\ManifestTwigExtension')
      ->addTag('twig.extension');
  }

}
