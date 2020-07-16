<?php

declare(strict_types=1);

namespace App\Service\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class Subscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection): void {
    if ($route = $collection->get('entity.node.canonical')) {
      $route->setDefault('_controller', '\App\Service\Routing\Router::view');
    }

    if ($route = $collection->get('entity.node.preview')) {
      $route->setDefault('_controller', '\App\Service\Routing\Router::preview');
    }

    if ($route = $collection->get('entity.node.revision')) {
      $route->setDefault('_controller', '\App\Service\Routing\Router::revision');
    }

    if ($route = $collection->get('entity.taxonomy_term.canonical')) {
      $route->setDefault('_controller', '\App\Controller\TaxonomyController::disable');
    }

    if ($route = $collection->get('entity.user.canonical')) {
      $route->setDefault('_controller', '\App\Controller\UserController::view');
    }
  }

}
