<?php

declare(strict_types=1);

namespace App\Service\EntityView;

/**
 * Entity view interface.
 */
interface EntityViewInterface {

  /**
   * Get view.
   */
  public function get(string $definition = 'full'): EntityViewDtoInterface;

}
