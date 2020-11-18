<?php

declare(strict_types=1);

namespace App\Routes;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines the company routes collection.
 */
class CompanyRoutes {

  /**
   * {@inheritdoc}
   */
  public function collection(): RouteCollection {
    $route_collection = new RouteCollection();
    $route_collection->add('entity.company.settings', $this->settings());
    $route_collection->add('entity.company.collection', $this->index());
    $route_collection->add('entity.company.add_form', $this->add());
    $route_collection->add('entity.company.edit_form', $this->edit());
    $route_collection->add('entity.company.delete_form', $this->delete());
    return $route_collection;
  }

  /**
   * Defines the settings route.
   */
  private function settings(): Route {
    return new Route(
      'admin/structure/company',
      [
        '_form' => '\App\Form\Company\SettingsForm',
        '_title' => 'Company',
      ],
      [
        '_permission' => 'administer company',
      ]
    );
  }

  /**
   * Defines the index route.
   */
  private function index(): Route {
    return new Route(
      '/admin/content/company',
      [
        '_entity_list' => 'company',
        '_title' => 'Companies',
      ],
      [
        '_permission' => 'administer company',
      ]
    );
  }

  /**
   * Defines the add route.
   */
  private function add(): Route {
    return new Route(
      '/admin/content/company/add',
      [
        '_entity_form' => 'company.add',
        '_title' => 'Add Company',
      ],
      [
        '_entity_create_access' => 'company',
      ]
    );
  }

  /**
   * Defines the edit route.
   */
  private function edit(): Route {
    return new Route(
      '/admin/content/company/{company}/edit',
      [
        '_entity_form' => 'company.edit',
        '_title' => 'Edit Company',
      ],
      [
        '_entity_access' => 'company.edit',
      ]
    );
  }

  /**
   * Defines the delete route.
   */
  private function delete(): Route {
    return new Route(
      '/admin/content/company/{company}/delete',
      [
        '_entity_form' => 'company.delete',
        '_title' => 'Delete Company',
      ],
      [
        '_entity_access' => 'company.delete',
      ]
    );
  }

}
