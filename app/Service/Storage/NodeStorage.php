<?php

declare(strict_types=1);

namespace App\Service\Storage;

use App\Service\Entity\Node;
use Drupal\node\NodeStorage as Base;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * Dependency injection container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  protected $container;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $container,
      $entity_type,
      $container->get('database'),
      $container->get('entity_field.manager'),
      $container->get('cache.entity'),
      $container->get('language_manager'),
      $container->get('entity.memory_cache'),
      $container->get('entity_type.bundle.info'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(
    ContainerInterface $container,
    EntityTypeInterface $entity_type,
    Connection $database,
    EntityFieldManagerInterface $entity_field_manager,
    CacheBackendInterface $cache,
    LanguageManagerInterface $language_manager,
    MemoryCacheInterface $memory_cache = NULL,
    EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL,
    EntityTypeManagerInterface $entity_type_manager = NULL
  ) {
    $this->container = $container;
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
    if (!$records) {
      return [];
    }

    $field_names = $this->tableMapping->getFieldNames($this->baseTable);
    if ($this->revisionTable) {
      $field_names = array_unique(array_merge($field_names, $this->tableMapping->getFieldNames($this->revisionTable)));
    }

    $values = [];
    foreach ($records as $id => $record) {
      // Set the entity class to use dynamically, based upon the record type.
      $this->entityClass = $this->getEntityTypeClass($record->type);
      $values[$id] = [];
      foreach ($field_names as $field_name) {
        $field_columns = $this->tableMapping->getColumnNames($field_name);
        if (count($field_columns) > 1) {
          $definition_columns = $this->fieldStorageDefinitions[$field_name]->getColumns();
          foreach ($field_columns as $property_name => $column_name) {
            if (property_exists($record, $column_name)) {
              $values[$id][$field_name][LanguageInterface::LANGCODE_DEFAULT][$property_name] = !empty($definition_columns[$property_name]['serialize']) ? unserialize($record->{$column_name}) : $record->{$column_name};
              unset($record->{$column_name});
            }
          }
        }
        else {
          $column_name = reset($field_columns);
          if (property_exists($record, $column_name)) {
            $columns = $this->fieldStorageDefinitions[$field_name]->getColumns();
            $column = reset($columns);
            $values[$id][$field_name][LanguageInterface::LANGCODE_DEFAULT] = !empty($column['serialize']) ? unserialize($record->{$column_name}) : $record->{$column_name};
            unset($record->{$column_name});
          }
        }
      }

      foreach ($record as $name => $value) {
        $values[$id][$name][LanguageInterface::LANGCODE_DEFAULT] = $value;
      }
    }

    $translations = array_fill_keys(array_keys($values), []);

    $this->loadFromSharedTables($values, $translations, $load_from_revision);
    $this->loadFromDedicatedTables($values, $load_from_revision);

    $entities = [];
    foreach ($values as $id => $entity_values) {
      $bundle = $this->bundleKey ? $entity_values[$this->bundleKey][LanguageInterface::LANGCODE_DEFAULT] : FALSE;
      // Should be able to push the container through onto the class...
      $entities[$id] = $this->entityClass::createInstance($this->container, $entity_values, $this->entityTypeId, $bundle, array_keys($translations[$id]));
    }

    return $entities;
  }

}
