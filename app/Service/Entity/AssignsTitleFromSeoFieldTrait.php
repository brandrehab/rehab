<?php

declare(strict_types=1);

namespace App\Service\Entity;

/**
 * Assigns title from seo field trait.
 */
trait AssignsTitleFromSeoFieldTrait {

  /**
   * Assign the value from meta title field to the title field.
   */
  public function assignTitleFromSeoField(): void {
    $meta_title = $this->get('field_seo_and_social_media')->entity->get('field_meta_title')->value;
    $this->setTitle($meta_title);
  }

}
