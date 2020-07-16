<?php

declare(strict_types=1);

namespace App\Entity;

use App\EntityView\PageEntityView;
use App\Service\Entity\Node;

/**
 * Page entity.
 */
class Page extends Node implements PageInterface {

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = PageEntityView::class;

}
