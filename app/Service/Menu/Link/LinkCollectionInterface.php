<?php

declare(strict_types=1);

namespace App\Service\Menu\Link;

/**
 * Link collection interface.
 */
interface LinkCollectionInterface extends \Iterator {

  /**
   * Add a link to the collection.
   */
  public function add(LinkInterface $link): void;

}
