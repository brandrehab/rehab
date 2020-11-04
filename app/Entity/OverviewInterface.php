<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Entity\NodeInterface;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Provides an interface defining a overview entity.
 */
interface OverviewInterface extends NodeInterface {

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
