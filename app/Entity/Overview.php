<?php

declare(strict_types=1);

namespace App\Entity;

use App\EntityView\OverviewEntityView;
use App\Service\Entity\Node;

/**
 * Overview entity.
 */
class Overview extends Node implements OverviewInterface {

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = OverviewEntityView::class;

}
