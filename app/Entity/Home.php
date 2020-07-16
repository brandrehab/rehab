<?php

declare(strict_types=1);

namespace App\Entity;

use App\EntityView\HomeEntityView;
use App\Service\Entity\Node;

/**
 * Home entity.
 */
class Home extends Node implements HomeInterface {

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = HomeEntityView::class;

}
