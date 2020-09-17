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
   * Loads the block services.
   */
  public static function load(ContainerBuilder $container): void {
    self::composerOutdated($container);
    self::twigClear($container);
    self::drupalStandardsCompliant($container);
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

}
