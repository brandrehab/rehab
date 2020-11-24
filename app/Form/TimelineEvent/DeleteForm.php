<?php

declare(strict_types=1);

namespace App\Form\TimelineEvent;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a form for deleting a timeline event entity.
 */
class DeleteForm extends ContentEntityDeleteForm {

  /**
   * Sets the page title.
   */
  public function getQuestion(): TranslatableMarkup {
    return $this->t("Are you sure");
  }

}
