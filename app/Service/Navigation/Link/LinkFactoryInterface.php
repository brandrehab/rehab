<?php

declare(strict_types=1);

namespace App\Service\Navigation\Link;

/**
 * Link factory interface.
 */
interface LinkFactoryInterface {

  /**
   * Create a new link instance.
   */
  public function createLink(): LinkInterface;

  /**
   * Create a new link collection instance.
   */
  public function createCollection(): LinkCollectionInterface;

}
