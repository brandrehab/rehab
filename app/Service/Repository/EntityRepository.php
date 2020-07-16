<?php

declare(strict_types=1);

namespace App\Service\Repository;

use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Entity repository class.
 */
abstract class EntityRepository implements EntityRepositoryInterface {

  /**
   * The bundle.
   *
   * @var string
   */
  protected $bundle;

  /**
   * The entity type.
   *
   * @var string
   */
  protected $entityType;

  /**
   * The entity reference.
   *
   * @var string
   */
  protected $entityReference;

  /**
   * The Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Class constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    AccountProxyInterface $current_user
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  /**
   * A base query that is used as the starting point for follow up queries.
   */
  abstract protected function getBaseQuery() : QueryInterface;

  /**
   * The entity storage.
   */
  abstract protected function getStorage(): EntityStorageInterface;

  /**
   * Find with matching id.
   */
  public function find(int $id) : ?EntityInterface {
    $query = $this->getBaseQuery();
    $query->condition($this->entityReference, $id);
    return $this->getResult($query);
  }

  /**
   * Find all with matching ids.
   */
  public function findAll(array $ids) : ?array {
    $query = $this->getBaseQuery();
    $query->condition($this->entityReference, $ids, 'IN');
    return $this->getResults($query);
  }

  /**
   * Find all entities.
   */
  public function all() : ?array {
    return $this->getResults($this->getBaseQuery());
  }

  /**
   * Load multiple entities from storage.
   */
  protected function loadMultiple(array $ids = NULL): ?array {
    return $this->getStorage()->loadMultiple($ids);
  }

  /**
   * Load a single entity from storage.
   */
  protected function loadSingle(int $id = NULL): ?EntityInterface {
    return $this->getStorage()->load($id);
  }

  /**
   * Helper function to get the results and load them as entities.
   */
  protected function getResults(QueryInterface $query): ?array {
    if (!$ids = $query->execute()) {
      return NULL;
    }
    return $this->loadMultiple($ids);
  }

  /**
   * Helper function to get a result and load it as an entity.
   */
  protected function getResult(QueryInterface $query): ?EntityInterface {
    if (!$id = $query->execute()) {
      return NULL;
    }
    return $this->loadSingle(reset($id));
  }

}
