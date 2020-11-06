<?php

declare(strict_types=1);

namespace App\Service\Navigation\Link;

/**
 * Link factory class.
 */
class LinkFactory implements LinkFactoryInterface {

  /**
   * Create a new link instance.
   */
  public function createLink(): LinkInterface {
    return new Link();
  }

  /**
   * Create a new link collection instance.
   */
  public function createCollection(): LinkCollectionInterface {
    return new LinkCollection();
  }

}
