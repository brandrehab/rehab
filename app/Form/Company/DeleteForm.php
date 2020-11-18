<?php

declare(strict_types=1);

namespace App\Form\Company;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a form for deleting a company entity.
 */
class DeleteForm extends ContentEntityDeleteForm {

  /**
   * Sets the page title.
   */
  public function getQuestion(): TranslatableMarkup {
    return $this->t("Are you sure");
  }

}
