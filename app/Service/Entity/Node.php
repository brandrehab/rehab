<?php

declare(strict_types=1);

namespace App\Service\Entity;

use App\Service\EntityView\EntityViewInterface;
use Drupal\node\Entity\Node as NodeBase;

/**
 * Node entity.
 */
abstract class Node extends NodeBase implements NodeInterface {

  /**
   * These are the fields we care about.
   */
  public function entityView(): EntityViewInterface {
    $entity_view_classname = $this->entityView;
    return new $entity_view_classname($this);
  }

}
