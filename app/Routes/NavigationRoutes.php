<?php

declare(strict_types=1);

namespace App\Routes;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines the navigation routes collection.
 */
class NavigationRoutes {

  /**
   * {@inheritdoc}
   */
  public function collection(): RouteCollection {
    $route_collection = new RouteCollection();
    $route_collection->add('entity.navigation.settings', $this->settings());
    $route_collection->add('entity.navigation.collection', $this->index());
    $route_collection->add('entity.navigation.add_form', $this->add());
    $route_collection->add('entity.navigation.edit_form', $this->edit());
    $route_collection->add('entity.navigation.delete_form', $this->delete());
    return $route_collection;
  }

  /**
   * Defines the settings route.
   */
  private function settings(): Route {
    return new Route(
      'admin/structure/navigation',
      [
        '_form' => '\App\Form\Navigation\SettingsForm',
        '_title' => 'Navigation',
      ],
      [
        '_permission' => 'administer navigation',
      ]
    );
  }

  /**
   * Defines the index route.
   */
  private function index(): Route {
    return new Route(
      '/admin/content/navigation',
      [
        '_entity_list' => 'navigation',
        '_title' => 'Navigations',
      ],
      [
        '_permission' => 'administer navigation',
      ]
    );
  }

  /**
   * Defines the add route.
   */
  private function add(): Route {
    return new Route(
      '/admin/content/navigation/add',
      [
        '_entity_form' => 'navigation.add',
        '_title' => 'Add Navigation',
      ],
      [
        '_entity_create_access' => 'navigation',
      ]
    );
  }

  /**
   * Defines the edit route.
   */
  private function edit(): Route {
    return new Route(
      '/admin/content/navigation/{navigation}/edit',
      [
        '_entity_form' => 'navigation.edit',
        '_title' => 'Edit Navigation',
      ],
      [
        '_entity_access' => 'navigation.edit',
      ]
    );
  }

  /**
   * Defines the delete route.
   */
  private function delete(): Route {
    return new Route(
      '/admin/content/navigation/{navigation}/delete',
      [
        '_entity_form' => 'navigation.delete',
        '_title' => 'Delete Navigation',
      ],
      [
        '_entity_access' => 'navigation.delete',
      ]
    );
  }

}
