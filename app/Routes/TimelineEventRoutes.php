<?php

declare(strict_types=1);

namespace App\Routes;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines the timeline event routes collection.
 */
class TimelineEventRoutes {

  /**
   * {@inheritdoc}
   */
  public function collection(): RouteCollection {
    $route_collection = new RouteCollection();
    $route_collection->add('entity.timeline_event.settings', $this->settings());
    $route_collection->add('entity.timeline_event.collection', $this->index());
    $route_collection->add('entity.timeline_event.add_form', $this->add());
    $route_collection->add('entity.timeline_event.edit_form', $this->edit());
    $route_collection->add('entity.timeline_event.delete_form', $this->delete());
    return $route_collection;
  }

  /**
   * Defines the settings route.
   */
  private function settings(): Route {
    return new Route(
      'admin/structure/timeline-event',
      [
        '_form' => '\App\Form\TimelineEvent\SettingsForm',
        '_title' => 'Timeline Event',
      ],
      [
        '_permission' => 'administer timeline',
      ]
    );
  }

  /**
   * Defines the index route.
   */
  private function index(): Route {
    return new Route(
      '/admin/content/timeline-event',
      [
        '_entity_list' => 'timeline_event',
        '_title' => 'Timeline Events',
      ],
      [
        '_permission' => 'administer timeline',
      ]
    );
  }

  /**
   * Defines the add route.
   */
  private function add(): Route {
    return new Route(
      '/admin/content/timeline-event/add',
      [
        '_entity_form' => 'timeline_event.add',
        '_title' => 'Add Timeline Event',
      ],
      [
        '_entity_create_access' => 'timeline_event',
      ]
    );
  }

  /**
   * Defines the edit route.
   */
  private function edit(): Route {
    return new Route(
      '/admin/content/timeline-event/{timeline_event}/edit',
      [
        '_entity_form' => 'timeline_event.edit',
        '_title' => 'Edit Timeline Event',
      ],
      [
        '_entity_access' => 'timeline_event.edit',
      ]
    );
  }

  /**
   * Defines the delete route.
   */
  private function delete(): Route {
    return new Route(
      '/admin/content/timeline-event/{timeline_event}/delete',
      [
        '_entity_form' => 'timeline_event.delete',
        '_title' => 'Delete Timeline Event',
      ],
      [
        '_entity_access' => 'timeline_event.delete',
      ]
    );
  }

}
