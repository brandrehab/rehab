<?php

declare(strict_types=1);

namespace App\ServiceProvider;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Routing service provider class.
 */
class RoutingServiceProvider {

  /**
   * Loads the routing services.
   */
  public static function load(ContainerBuilder $container): void {
    self::subscriber($container);
  }

  /**
   * Route subscriber.
   */
  private static function subscriber(ContainerBuilder $container): void {
    $container->register('app.route_subscriber', 'App\Service\Routing\Subscriber')
      ->addTag('event_subscriber');
  }

}
