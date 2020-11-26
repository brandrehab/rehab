<?php

declare(strict_types=1);

namespace App\Service\Storage;

use App\Service\Entity\Node;
use Drupal\node\NodeStorage as Base;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Extends base node storage to assign entities base on node type.
 */
abstract class NodeStorage extends Base implements NodeStorageInterface {

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
