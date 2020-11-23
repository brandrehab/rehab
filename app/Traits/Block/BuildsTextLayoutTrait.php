<?php

declare(strict_types=1);

namespace App\Traits\Block;

use Drupal\Core\Block\BlockManagerInterface;

/**
 * Builds the text layout render array.
 */
trait BuildsTextLayoutTrait {

  /**
   * Block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected BlockManagerInterface $blockManager;

  /**
   * Gets the text render array.
   */
  protected function getTextLayout(?string $val): ?array {
    if (!$val) {
      return NULL;
    }

    return $this->blockManager->createInstance('app.layouts.text', [
      'text' => $val,
    ])->build();
  }

}
