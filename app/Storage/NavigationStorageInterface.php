<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\NavigationInterface;
use App\Service\Navigation\NavigationBuilderInterface;
use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines an interface for navigation storage classes.
 */
interface NavigationStorageInterface extends ContentEntityStorageInterface {

  /**
   * Get the navigation builder.
   */
  public function getNavigationBuilder(): NavigationBuilderInterface;

  /**
   * Get a navigation by name.
   */
  public function getByName(string $navigation_name): ?NavigationInterface;

  /**
   * Get a navigation by the associated menu name.
   */
  public function getByMenuId(string $menu_id): ?NavigationInterface;

}
