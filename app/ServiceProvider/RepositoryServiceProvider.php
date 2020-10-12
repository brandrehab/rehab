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
    self::article($container);
    self::error($container);
    self::home($container);
    self::overview($container);
    self::page($container);
    self::service($container);
    self::menu($container);
  }

  /**
   * Article repository.
   */
  private static function article(ContainerBuilder $container): void {
    $container->register('app.repository.article', 'App\Repository\ArticleRepository')
      ->addArgument(new Reference('entity_type.manager'))
      ->addArgument(new Reference('current_user'));
  }

  /**
   * Error repository.
   */
  private static function error(ContainerBuilder $container): void {
    $container->register('app.repository.error', 'App\Repository\ErrorRepository')
      ->addArgument(new Reference('entity_type.manager'))
      ->addArgument(new Reference('current_user'));
  }

  /**
   * Home repository.
   */
  private static function home(ContainerBuilder $container): void {
    $container->register('app.repository.home', 'App\Repository\HomeRepository')
      ->addArgument(new Reference('entity_type.manager'))
      ->addArgument(new Reference('current_user'));
  }

  /**
   * Overview repository.
   */
  private static function overview(ContainerBuilder $container): void {
    $container->register('app.repository.overview', 'App\Repository\OverviewRepository')
      ->addArgument(new Reference('entity_type.manager'))
      ->addArgument(new Reference('current_user'));
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
   * Service repository.
   */
  private static function service(ContainerBuilder $container): void {
    $container->register('app.repository.service', 'App\Repository\ServiceRepository')
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
