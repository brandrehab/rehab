<?php

declare(strict_types=1);

namespace App\Entity;

use App\EntityView\ServiceEntityView;
use App\Service\Entity\Node;

/**
 * Service entity.
 */
class Service extends Node implements ServiceInterface {

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = ServiceEntityView::class;

}
