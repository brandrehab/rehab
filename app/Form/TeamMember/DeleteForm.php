<?php

declare(strict_types=1);

namespace App\Form\TeamMember;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a form for deleting a team member entity.
 */
class DeleteForm extends ContentEntityDeleteForm {

  /**
   * Sets the page title.
   */
  public function getQuestion(): TranslatableMarkup {
    return $this->t("Are you sure");
  }

}
