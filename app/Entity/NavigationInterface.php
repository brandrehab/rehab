<?php

namespace App\Entity;

use App\Service\Navigation\Link\LinkCollectionInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a navigation entity type.
 */
interface NavigationInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the navigation creation timestamp.
   */
  public function getCreatedTime(): string;

  /**
   * Gets the name of the navigation.
   */
  public function getName(): string;

  /**
   * Gets a built navigation.
   */
  public function build(?int $min_depth = 1, ?int $max_depth = 1, ?string $root = ''): LinkCollectionInterface;

  /**
   * Gets nids from a navigation as a one-dimensional array.
   */
  public function nids(?int $min_depth = 1, ?int $max_depth = 1, ?string $root = ''): array;

  /**
   * Sets the navigationr creation timestamp.
   */
  public function setCreatedTime(string $timestamp): NavigationInterface;

}
