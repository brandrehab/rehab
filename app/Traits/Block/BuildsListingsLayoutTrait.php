<?php

declare(strict_types=1);

namespace App\Traits\Block;

use Drupal\Core\Block\BlockManagerInterface;

/**
 * Builds the listings layout render array.
 */
trait BuildsListingsLayoutTrait {

  /**
   * Block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected BlockManagerInterface $blockManager;

  /**
   * Gets the listings render array.
   */
  protected function getListingsLayout(?array $val): ?array {
    if (!$val) {
      return NULL;
    }

    return $this->blockManager->createInstance('app.layouts.listings', [
      'node' => $this->currentNode,
      'listings' => $val,
    ])->build();
  }

}
