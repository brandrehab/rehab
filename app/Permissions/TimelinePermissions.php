<?php

declare(strict_types=1);

namespace App\Permissions;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides dynamic permissions for timeline event entities.
 */
class TimelinePermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of team member permissions.
   */
  public function collection(): array {
    return [
      'add timeline event' => $this->add(),
      'edit timeline event' => $this->edit(),
      'delete timeline event' => $this->delete(),
      'administer timeline' => $this->administer(),
    ];
  }

  /**
   * Defines the add timeline event permission.
   */
  private function add(): array {
    return [
      'title' => $this->t('Add Timeline Event'),
    ];
  }

  /**
   * Defines the edit timeline event permission.
   */
  private function edit(): array {
    return [
      'title' => $this->t('Edit Timeline Event'),
    ];
  }

  /**
   * Defines the delete timeline event permission.
   */
  private function delete(): array {
    return [
      'title' => $this->t('Delete Timeline Event'),
    ];
  }

  /**
   * Defines the administer timeline permission.
   */
  private function administer(): array {
    return [
      'title' => $this->t('Administer Timeline'),
      'restrict access' => TRUE,
    ];
  }

}
