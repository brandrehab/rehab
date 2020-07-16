<?php

declare(strict_types=1);

namespace App\Service\Storage;

use Drupal\node\Entity\Node;
use Drupal\node\NodeStorage as Base;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class NodeStorage.
 */
class NodeStorage extends Base {

  /**
   * Node entity types and associated classes.
   *
   * @var array
   */
  static protected $nodeEntityTypes = [];

  /**
   * Class constructor.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    Connection $database,
    EntityFieldManagerInterface $entity_field_manager,
    CacheBackendInterface $cache,
    LanguageManagerInterface $language_manager,
    MemoryCacheInterface $memory_cache = NULL,
    EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL,
    EntityTypeManagerInterface $entity_type_manager = NULL
  ) {
    if ($entity_type_manager) {
      if (!self::$nodeEntityTypes) {
        $this->addEntityTypeClasses($entity_type_manager);
      }
    }
    parent::__construct($entity_type, $database, $entity_field_manager, $cache, $language_manager, $memory_cache, $entity_type_bundle_info, $entity_type_manager);
  }

  /**
   * Add node type classes.
   */
  public function addEntityTypeClasses(EntityTypeManagerInterface $entity_type_manager): void {
    $node_types = $entity_type_manager->getStorage('node_type')->loadMultiple();
    foreach ($node_types as $node_type) {
      $class_name = str_replace('_', '', ucwords($node_type->id(), '_'));
      $namespaced_class = 'App\\Entity\\' . $class_name;
      if (class_exists($namespaced_class)) {
        self::$nodeEntityTypes[$node_type->id()] = $namespaced_class;
        continue;
      }
      self::$nodeEntityTypes[$node_type->id()] = Node::class;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function doCreate(array $values) {
    $this->entityClass = $this->getEntityTypeClass($values['type']);

    return parent::doCreate($values);
  }

  /**
   * Returns the entity class of a node type.
   */
  public function getEntityTypeClass(string $node_type): string {
    if (array_key_exists($node_type, self::$nodeEntityTypes)) {
      return self::$nodeEntityTypes[$node_type];
    }
    return Node::class;
  }

  /**
   * {@inheritdoc}
   */
  protected function mapFromStorageRecords(array $records, $load_from_revision = FALSE): array {
    $node_storage_records = [];
    $nodes = [];

    foreach ($records as $id => $record) {
      $node_storage_records[$this->getEntityTypeClass($record->type)][$id] = $record;
    }

    foreach ($node_storage_records as $node_storage_class => $node_storage_record) {
      $this->entityClass = $node_storage_class;
      $node_entities = parent::mapFromStorageRecords($node_storage_record, $load_from_revision);
      $nodes = $nodes + $node_entities;
    }

    return $nodes;
  }

}
