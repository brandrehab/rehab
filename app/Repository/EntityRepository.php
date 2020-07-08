<?php

namespace App\Repository;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Entity repository class.
 */
class EntityRepository {

  /**
   * The Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Class constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entiyTypeManager = $entity_type_manager;
  }

}
