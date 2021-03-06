<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Block service provider class.
 */
class BlockServiceProvider {

  /**
   * Registers the block services.
   */
  public static function register(ContainerBuilder $container): void {
    self::head($container);
  }

  /**
   * Head block service.
   */
  private static function head(ContainerBuilder $container): void {
    $container->register('app.block.head', 'App\Service\Block\HeadService');
  }

}
