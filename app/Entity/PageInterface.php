<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Entity\NodeInterface;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Provides an interface defining a page entity.
 */
interface PageInterface extends NodeInterface {

  /**
   * Get the optional entity layouts.
   */
  public function getLayouts(): ?array;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void;

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE): void;

}
