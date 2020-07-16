<?php

declare(strict_types=1);

namespace Drupal\app;

use App\ServiceProvider\RoutingServiceProvider;
use App\ServiceProvider\MenuServiceProvider;
use App\ServiceProvider\RepositoryServiceProvider;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Interacts with the container.
 */
class AppServiceProvider extends ServiceProviderBase {

  /**
   * Register providers with the container.
   */
  public function register(ContainerBuilder $container): void {
    RoutingServiceProvider::load($container);
    MenuServiceProvider::load($container);
    RepositoryServiceProvider::load($container);
  }

}
