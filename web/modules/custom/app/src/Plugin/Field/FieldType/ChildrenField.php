<?php

declare(strict_types=1);

namespace Drupal\app\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Children field type.
 *
 * @FieldType(
 *   id = "children_field",
 *   label = @Translation("Children"),
 *   description = @Translation("Provides a list of available content types"),
 *   default_widget = "children_widget",
 *   default_formatter = "children_formatter"
 * )
 */
class ChildrenField extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {
    $label = new TranslatableMarkup('Children');
    $properties['value'] = DataDefinition::create('string')
      ->setLabel($label->__toString())
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {
    $schema = [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
          'description' => 'Content type id of children',
        ],
      ],
    ];
    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition): array {
    $values['value'] = rand(1, 100);
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

}
