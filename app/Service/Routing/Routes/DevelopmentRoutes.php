<?php

declare(strict_types=1);

namespace App\Service\Routing\Routes;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines the development routes collection.
 */
class DevelopmentRoutes {

  /**
   * {@inheritdoc}
   */
  public function collection(): RouteCollection {
    $route_collection = new RouteCollection();
    $route_collection->add('app.controller.development.reload', $this->hotReload());
    $route_collection->add('app.controller.development.test', $this->test());
    return $route_collection;
  }

  /**
   * Defines the hot reload route.
   */
  private function hotReload(): Route {
    return new Route(
      '/dev/hot-reload',
      [
        '_controller' => '\App\Controller\DevelopmentController::hotReload',
      ],
      [
        '_permission' => 'access content',
      ],
      [
        'no_cache' => TRUE,
      ]
    );
  }

  /**
   * Defines the test route.
   */
  private function test(): Route {
    return new Route(
      '/dev/test',
      [
        '_controller' => '\App\Controller\DevelopmentController::test',
      ],
      [
        '_permission' => 'access content',
      ],
      [
        'no_cache' => TRUE,
      ]
    );
  }

}
