<?php

declare(strict_types=1);

namespace App\Permissions;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides dynamic permissions for company entities.
 */
class CompanyPermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of company permissions.
   */
  public function collection(): array {
    return [
      'add company' => $this->add(),
      'edit company' => $this->edit(),
      'delete company' => $this->delete(),
      'administer company' => $this->administer(),
    ];
  }

  /**
   * Defines the add company permission.
   */
  private function add(): array {
    return [
      'title' => $this->t('Add Company'),
    ];
  }

  /**
   * Defines the edit company permission.
   */
  private function edit(): array {
    return [
      'title' => $this->t('Edit Company'),
    ];
  }

  /**
   * Defines the delete company permission.
   */
  private function delete(): array {
    return [
      'title' => $this->t('Delete Company'),
    ];
  }

  /**
   * Defines the administer team permission.
   */
  private function administer(): array {
    return [
      'title' => $this->t('Administer Company'),
      'restrict access' => TRUE,
    ];
  }

}
