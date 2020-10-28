<?php

declare(strict_types=1);

namespace App\Service\ServiceProvider;

use App\ServiceProvider\RoutingServiceProvider;
use App\ServiceProvider\MenuServiceProvider;
use App\ServiceProvider\RepositoryServiceProvider;
use App\ServiceProvider\BlockServiceProvider;
use App\ServiceProvider\CommandServiceProvider;
use App\ServiceProvider\TwigExtensionServiceProvider;
use Drupal\Core\DependencyInjection\ContainerBuilder;

/**
 * Registers the collection of available app service providers.
 */
class ServiceProviderCollection {

  /**
   * Registers the block services.
   */
  public static function register(ContainerBuilder $container): void {
    RoutingServiceProvider::register($container);
    MenuServiceProvider::register($container);
    RepositoryServiceProvider::register($container);
    BlockServiceProvider::register($container);
    CommandServiceProvider::register($container);
    TwigExtensionServiceProvider::register($container);
  }

}
