<?php

declare(strict_types=1);

namespace App\Service\Storage;

use App\Repository\MenuRepositoryInterface;
use App\Service\Entity\Node;
use Drupal\node\NodeStorage as Base;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Session\AccountProxyInterface;
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
 * Extends base node storage to assign entities base on node type.
 */
class NodeStorage extends Base {

  /**
   * Node entity types (bundles) and associated classes.
   *
   * @var array
   */
  static protected array $nodeEntityTypes = [];

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * The menu repository.
   *
   * @var \App\Repository\MenuRepositoryInterface
   */
  protected MenuRepositoryInterface $menuRepository;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(
    ContainerInterface $container,
    EntityTypeInterface $entity_type
  ): self {
    return new self(
      $entity_type,
      $container->get('database'),
      $container->get('entity_field.manager'),
      $container->get('cache.entity'),
      $container->get('language_manager'),
      $container->get('entity.memory_cache'),
      $container->get('entity_type.bundle.info'),
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('app.repository.menu')
    );
  }

  /**
   * Constructs a SqlContentEntityStorage object.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    Connection $database,
    EntityFieldManagerInterface $entity_field_manager,
    CacheBackendInterface $cache,
    LanguageManagerInterface $language_manager,
    MemoryCacheInterface $memory_cache,
    EntityTypeBundleInfoInterface $entity_type_bundle_info,
    EntityTypeManagerInterface $entity_type_manager,
    AccountProxyInterface $current_user,
    MenuRepositoryInterface $menu_repository
  ) {
    $this->currentUser = $current_user;
    $this->menuRepository = $menu_repository;
    parent::__construct($entity_type, $database, $entity_field_manager, $cache, $language_manager, $memory_cache, $entity_type_bundle_info, $entity_type_manager);

    if (!self::$nodeEntityTypes) {
      $this->setNodeEntityTypes();
    }
  }

  /**
   * Matches Entity types to node type specific classes.
   */
  protected function setNodeEntityTypes(): void {
    $node_types = $this->entityTypeManager->getStorage('node_type')->loadMultiple();
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
   * Get node entity class of given type.
   */
  protected function getNodeEntityType(string $node_type): string {
    if (array_key_exists($node_type, self::$nodeEntityTypes)) {
      return self::$nodeEntityTypes[$node_type];
    }
    return Node::class;
  }

  /**
   * Get the menu repository.
   */
  public function getMenuRepository(): MenuRepositoryInterface {
    return $this->menuRepository;
  }

  /**
   * {@inheritdoc}
   */
  public function getBundleQuery(string $bundle, string $conjugation = 'AND'): QueryInterface {
    $query = $this->getQuery($conjugation)
      ->condition('type', $bundle);

    if ($this->currentUser->isAnonymous()) {
      $query->condition('status', Node::PUBLISHED);
    }
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  protected function doCreate(array $values) {
    $this->entityClass = $this->getNodeEntityType($values['type']);
    return parent::doCreate($values);
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
      $this->entityClass = $this->getNodeEntityType($entity_values['type']['x-default']);
      $bundle = $this->bundleKey ? $entity_values[$this->bundleKey][LanguageInterface::LANGCODE_DEFAULT] : FALSE;
      $entities[$id] = new $this->entityClass($entity_values, $this->entityTypeId, $bundle, array_keys($translations[$id]));
    }

    return $entities;
  }

}
