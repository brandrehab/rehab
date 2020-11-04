<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Repository service provider class.
 */
class RepositoryServiceProvider {

  /**
   * Registers the repository services.
   */
  public static function register(ContainerBuilder $container): void {
    self::menu($container);
  }

  /**
   * Menu repository.
   */
  private static function menu(ContainerBuilder $container): void {
    $container->register('app.repository.menu', 'App\Repository\MenuRepository')
      ->AddArgument(new Reference('app.menu.main'))
      ->AddArgument(new Reference('app.menu.footer'))
      ->AddArgument(new Reference('app.menu.hidden'));
  }

}
