<?php

declare(strict_types=1);

namespace App\Service\EntityView;

/**
 * Entity view.
 */
abstract class EntityViewDto implements EntityViewDtoInterface {

  /**
   * Class constructor.
   */
  public function __construct(array $view) {
    foreach ($view as $property => $value) {
      if (!property_exists($this, $property)) {
        continue;
      }
      $this->$property = $value;
    }
  }

}
