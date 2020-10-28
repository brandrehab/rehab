<?php

declare(strict_types=1);

namespace App\Service\Routing\Routes;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines the team member routes collection.
 */
class TeamMemberRoutes {

  /**
   * {@inheritdoc}
   */
  public function collection(): RouteCollection {
    $route_collection = new RouteCollection();
    $route_collection->add('entity.team_member.settings', $this->settings());
    $route_collection->add('entity.team_member.collection', $this->index());
    $route_collection->add('entity.team_member.add_form', $this->add());
    $route_collection->add('entity.team_member.edit_form', $this->edit());
    $route_collection->add('entity.team_member.delete_form', $this->delete());
    return $route_collection;
  }

  /**
   * Defines the settings route.
   */
  private function settings(): Route {
    return new Route(
      'admin/structure/team-member',
      [
        '_form' => '\App\Form\TeamMember\SettingsForm',
        '_title' => 'Team Member',
      ],
      [
        '_permission' => 'administer team',
      ]
    );
  }

  /**
   * Defines the index route.
   */
  private function index(): Route {
    return new Route(
      '/admin/content/team-member',
      [
        '_entity_list' => 'team_member',
        '_title' => 'Team Members',
      ],
      [
        '_permission' => 'administer team',
      ]
    );
  }

  /**
   * Defines the add route.
   */
  private function add(): Route {
    return new Route(
      '/admin/content/team-member/add',
      [
        '_entity_form' => 'team_member.add',
        '_title' => 'Add Team Member',
      ],
      [
        '_entity_create_access' => 'team_member',
      ]
    );
  }

  /**
   * Defines the edit route.
   */
  private function edit(): Route {
    return new Route(
      '/admin/content/team-member/{team_member}/edit',
      [
        '_entity_form' => 'team_member.edit',
        '_title' => 'Edit Team Member',
      ],
      [
        '_entity_access' => 'team_member.edit',
      ]
    );
  }

  /**
   * Defines the delete route.
   */
  private function delete(): Route {
    return new Route(
      '/admin/content/team-member/{team_member}/delete',
      [
        '_entity_form' => 'team_member.delete',
        '_title' => 'Delete Team Member',
      ],
      [
        '_entity_access' => 'team_member.delete',
      ]
    );
  }

}
