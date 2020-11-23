<?php

declare(strict_types=1);

namespace App\Traits\Block;

use Drupal\Core\Block\BlockManagerInterface;

/**
 * Builds the team layout render array.
 */
trait BuildsTeamLayoutTrait {

  /**
   * Block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected BlockManagerInterface $blockManager;

  /**
   * Gets the team render array.
   */
  protected function getTeamLayout(?array $val): ?array {
    if (!$val) {
      return NULL;
    }

    return $this->blockManager->createInstance('app.layouts.team', [
      'team' => $val,
    ])->build();
  }

}
