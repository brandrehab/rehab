<?php

declare(strict_types=1);

namespace App\Service\Entity;

use App\Service\EntityView\EntityViewInterface;
use Drupal\node\NodeInterface as BaseNodeInterface;

/**
 * Node entity interface.
 */
interface NodeInterface extends BaseNodeInterface {

  /**
   * These are the fields we care about.
   */
  public function entityView(): EntityViewInterface;

}
