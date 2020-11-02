<?php

declare(strict_types=1);

namespace App\Command;

use Drush\Commands\DrushCommands;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityDefinitionUpdateManagerInterface;
use Drupal\Core\Database\Connection;

/**
 * App Entities command.
 */
class AppEntities extends DrushCommands {

  /**
   * Valid actions.
   *
   * @var array
   */
  private array $actions = [
    'enable',
    'uninstall',
  ];

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  private Connection $database;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private EntityTypeManagerInterface $entityTypeManager;

  /**
   * Entity definition update manager.
   *
   * @var \Drupal\Core\Entity\EntityDefinitionUpdateManagerInterface
   */
  private EntityDefinitionUpdateManagerInterface $entityDefinitionUpdateManager;

  /**
   * Class constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    Connection $database,
    EntityDefinitionUpdateManagerInterface $entity_definition_update_manager
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityDefinitionUpdateManager = $entity_definition_update_manager;
    $this->database = $database;
  }

  /**
   * Check whether drupal core or any contrib modules are out of date.
   *
   * @param string $action
   *   Action to be performed enable/uninstall.
   * @param string $entity
   *   Name of entity upon which to perform action.
   *
   * @command app:entities
   * @aliases ae
   * @usage drush app:entities [action]
   *   Actions for entities which are not defined with a module.
   */
  public function entities(string $action, string $entity): int {

    if (!in_array($action, $this->actions)) {
      $this->io()->error('Unrecognised action.');
      return self::EXIT_FAILURE;
    }

    if (!$this->{$action}($entity)) {
      return self::EXIT_FAILURE;
    }

    $this->io()->success('All done.');
    return self::EXIT_SUCCESS;
  }

  /**
   * Attempt to enable an entity.
   */
  protected function enable(string $entity): bool {
    $this->io()->title('Attempting to enable "' . $entity . '" entity.');

    if (!$this->exists($entity)) {
      $this->io()->error('Entity "' . $entity . '" does not exist.');
      return FALSE;
    }

    if ($this->enabled($entity)) {
      $this->io()->error('Entity "' . $entity . '" is already enabled.');
      return FALSE;
    }

    $this->entityDefinitionUpdateManager
      ->installEntityType($this->entityTypeManager->getDefinition($entity));

    return TRUE;
  }

  /**
   * Attempt to uninstall an entity.
   */
  protected function uninstall(string $entity): bool {
    $this->io()->title('Attempting to uninstall "' . $entity . '" entity.');

    if (!$this->exists($entity)) {
      $this->io()->error('Entity "' . $entity . '" does not exist.');
      return FALSE;
    }

    if ($this->removed($entity)) {
      $this->io()->error('Entity "' . $entity . '" is already uninstalled.');
      return FALSE;
    }

    $this->entityDefinitionUpdateManager
      ->uninstallEntityType($this->entityTypeManager->getDefinition($entity));

    return TRUE;
  }

  /**
   * Check whether an entity is enabled.
   */
  private function enabled(string $entity): bool {
    $definition = $this->entityTypeManager->getDefinition($entity);
    return $this->database->schema()->tableExists($definition->getBaseTable());
  }

  /**
   * Check whether an entity is removed.
   */
  private function removed(string $entity): bool {
    return !$this->enabled($entity);
  }

  /**
   * Check whether an entity exists (regardless of state).
   */
  private function exists(string $entity): bool {
    return $this->entityTypeManager->hasDefinition($entity);
  }

}
