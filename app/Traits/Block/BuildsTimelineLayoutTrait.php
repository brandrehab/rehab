<?php

declare(strict_types=1);

namespace App\Traits\Block;

use Drupal\Core\Block\BlockManagerInterface;

/**
 * Builds the timeline layout render array.
 */
trait BuildsTimelineLayoutTrait {

  /**
   * Block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected BlockManagerInterface $blockManager;

  /**
   * Gets the team render array.
   */
  protected function getTimelineLayout(?array $val): ?array {
    if (!$val) {
      return NULL;
    }

    return $this->blockManager->createInstance('app.layouts.timeline', [
      'timeline' => $val,
    ])->build();
  }

}
