<?php

declare(strict_types=1);

namespace App\Permissions;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides dynamic permissions for navigation entities.
 */
class NavigationPermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of navigation permissions.
   */
  public function collection(): array {
    return [
      'add navigation' => $this->add(),
      'edit navigation' => $this->edit(),
      'delete navigation' => $this->delete(),
      'administer navigation' => $this->administer(),
    ];
  }

  /**
   * Defines the add navigation permission.
   */
  private function add(): array {
    return [
      'title' => $this->t('Add Navigation'),
    ];
  }

  /**
   * Defines the edit navigation permission.
   */
  private function edit(): array {
    return [
      'title' => $this->t('Edit Navigation'),
    ];
  }

  /**
   * Defines the delete navigation permission.
   */
  private function delete(): array {
    return [
      'title' => $this->t('Delete Navigation'),
    ];
  }

  /**
   * Defines the administer navigation permission.
   */
  private function administer(): array {
    return [
      'title' => $this->t('Administer Navigation'),
      'restrict access' => TRUE,
    ];
  }

}
