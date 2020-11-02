<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Command service provider class.
 */
class CommandServiceProvider {

  /**
   * Registers the block services.
   */
  public static function register(ContainerBuilder $container): void {
    self::composerOutdated($container);
    self::twigClear($container);
    self::drupalStandardsCompliant($container);
    self::appEntities($container);
    self::faviconGenerator($container);
  }

  /**
   * Define composer:outdated (co) command.
   */
  private static function composerOutdated(ContainerBuilder $container): void {
    $container->register('app.command.composer_outdated', 'App\Command\ComposerOutdated')
      ->addTag('drush.command');
  }

  /**
   * Define twig:clear (tc) command.
   */
  private static function twigClear(ContainerBuilder $container): void {
    $container->register('app.command.twig_clear', 'App\Command\TwigClear')
      ->addArgument(new Reference('twig'))
      ->addTag('drush.command');
  }

  /**
   * Define standards:compliant (dsc) command.
   */
  private static function drupalStandardsCompliant(ContainerBuilder $container): void {
    $container->register('app.command.drupal_standards_compliant', 'App\Command\DrupalStandardsCompliant')
      ->addTag('drush.command');
  }

  /**
   * Define app:entities (ae) command.
   */
  private static function appEntities(ContainerBuilder $container): void {
    $container->register('app.command.entities', 'App\Command\AppEntities')
      ->addArgument(new Reference('entity_type.manager'))
      ->addArgument(new Reference('database'))
      ->addArgument(new Reference('entity.definition_update_manager'))
      ->addTag('drush.command');
  }

  /**
   * Define app:favicon (af) command.
   */
  private static function faviconGenerator(ContainerBuilder $container): void {
    $container->register('app.command.favicon_generator', 'App\Command\FaviconGenerator')
      ->addArgument(new Reference('file_system'))
      ->addArgument(new Reference('twig'))
      ->addTag('drush.command');
  }

}
