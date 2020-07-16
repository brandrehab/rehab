<?php

declare(strict_types=1);

namespace App\Entity;

use App\EntityView\ErrorEntityView;
use App\Service\Entity\Node;

/**
 * Error entity.
 */
class Error extends Node implements ErrorInterface {

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = ErrorEntityView::class;

}
