<?php

declare(strict_types=1);

namespace App\Service\EntityView;

use Drupal\node\NodeInterface;

/**
 * Node entity view interface.
 */
interface NodeEntityViewInterface extends EntityViewInterface {

  /**
   * Class constructor.
   */
  public function __construct(NodeInterface $entity);

}
