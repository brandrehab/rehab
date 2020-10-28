<?php

declare(strict_types=1);

namespace App\Service\Storage;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for team members.
 *
 * This extends the base storage class, adding required special handling for
 * team entities.
 */
class TeamMemberStorage extends SqlContentEntityStorage implements TeamMemberStorageInterface {
  // Add code here.
}
