<?php

declare(strict_types=1);

namespace App\Service\Permissions;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides dynamic permissions for team member entities.
 */
class TeamPermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of team member permissions.
   */
  public function collection(): array {
    return [
      'add team member' => $this->add(),
      'edit team member' => $this->edit(),
      'delete team member' => $this->delete(),
      'administer team' => $this->administer(),
    ];
  }

  /**
   * Defines the add team memeber permission.
   */
  private function add(): array {
    return [
      'title' => $this->t('Add Team Member'),
    ];
  }

  /**
   * Defines the edit team member permission.
   */
  private function edit(): array {
    return [
      'title' => $this->t('Edit Team Member'),
    ];
  }

  /**
   * Defines the delete team member permission.
   */
  private function delete(): array {
    return [
      'title' => $this->t('Delete Team Member'),
    ];
  }

  /**
   * Defines the administer team permission.
   */
  private function administer(): array {
    return [
      'title' => $this->t('Administer Team'),
      'restict access' => TRUE,
    ];
  }

}
