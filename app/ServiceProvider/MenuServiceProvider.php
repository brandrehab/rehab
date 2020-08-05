<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Menu service provider class.
 */
class MenuServiceProvider {

  /**
   * Loads the menus and menu services.
   */
  public static function load(ContainerBuilder $container): void {
    self::linkFactory($container);
    self::main($container);
    self::footer($container);
    self::hidden($container);
  }

  /**
   * Link factory service.
   */
  private static function linkFactory(ContainerBuilder $container): void {
    $container->register('app.menu.link.factory', 'App\Service\Menu\Link\LinkFactory');
  }

  /**
   * Main menu.
   */
  private static function main(ContainerBuilder $container): void {
    $container->register('app.menu.main', 'App\Menu\MainMenu')
      ->AddArgument(new Reference('menu.link_tree'))
      ->addArgument(new Reference('menu.active_trail'))
      ->addArgument(new Reference('path.matcher'))
      ->addArgument(new Reference('app.menu.link.factory'));
  }

  /**
   * Footer menu.
   */
  private static function footer(ContainerBuilder $container): void {
    $container->register('app.menu.footer', 'App\Menu\FooterMenu')
      ->AddArgument(new Reference('menu.link_tree'))
      ->addArgument(new Reference('menu.active_trail'))
      ->addArgument(new Reference('path.matcher'))
      ->addArgument(new Reference('app.menu.link.factory'));
  }

  /**
   * Hidden menu.
   */
  private static function hidden(ContainerBuilder $container): void {
    $container->register('app.menu.hidden', 'App\Menu\HiddenMenu')
      ->AddArgument(new Reference('menu.link_tree'))
      ->addArgument(new Reference('menu.active_trail'))
      ->addArgument(new Reference('path.matcher'))
      ->addArgument(new Reference('app.menu.link.factory'));
  }

}
