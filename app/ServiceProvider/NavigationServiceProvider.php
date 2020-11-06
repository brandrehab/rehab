<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Navigation service provider class.
 */
class NavigationServiceProvider {

  /**
   * Registers the navigation and link service.
   */
  public static function register(ContainerBuilder $container): void {
    self::linkFactory($container);
    self::builder($container);
  }

  /**
   * Link factory service.
   */
  private static function linkFactory(ContainerBuilder $container): void {
    $container->register('app.navigation.link.factory', 'App\Service\Navigation\Link\LinkFactory');
  }

  /**
   * Navigation builder service.
   */
  private static function builder(ContainerBuilder $container): void {
    $container->register('app.navigation.builder', 'App\Service\Navigation\NavigationBuilder')
      ->AddArgument(new Reference('menu.link_tree'))
      ->addArgument(new Reference('menu.active_trail'))
      ->addArgument(new Reference('path.matcher'))
      ->addArgument(new Reference('app.navigation.link.factory'));
  }

}
