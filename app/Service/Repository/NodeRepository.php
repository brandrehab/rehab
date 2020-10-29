<?php

declare(strict_types=1);

namespace App\Service\Repository;

use Drupal\node\Entity\Node;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\Query\QueryInterface;

/**
 * Entity repository class.
 */
class NodeRepository extends EntityRepository implements NodeRepositoryInterface {

  /**
   * {@inheritdoc}
   */
  protected string $entityType = 'node';

  /**
   * {@inheritdoc}
   */
  protected string $entityReference = 'nid';

  /**
   * {@inheritdoc}
   */
  protected function getStorage(): EntityStorageInterface {
    return $this->entityTypeManager->getStorage($this->entityType);
  }

  /**
   * {@inheritdoc}
   */
  protected function getBaseQuery(): QueryInterface {
    $query = $this->getStorage()->getQuery();

    if ($this->currentUser->isAnonymous()) {
      $query->condition('status', Node::PUBLISHED);
    }
    $query->condition('type', $this->bundle);
    $query->addTag('node_access');
    return $query;
  }

}
