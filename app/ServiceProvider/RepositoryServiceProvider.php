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
   * Loads the repository services.
   */
  public static function load(ContainerBuilder $container): void {
    self::page($container);
    self::menu($container);
  }

  /**
   * Page repository.
   */
  private static function page(ContainerBuilder $container): void {
    $container->register('app.repository.page', 'App\Repository\PageRepository')
      ->addArgument(new Reference('entity_type.manager'))
      ->addArgument(new Reference('current_user'));
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
