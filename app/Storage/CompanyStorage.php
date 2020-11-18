<?php

declare(strict_types=1);

namespace App\Storage;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for companies.
 *
 * This extends the base storage class, adding required special handling for
 * companies.
 */
class CompanyStorage extends SqlContentEntityStorage implements CompanyStorageInterface {

}
