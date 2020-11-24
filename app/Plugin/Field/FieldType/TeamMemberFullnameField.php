<?php

declare(strict_types=1);

namespace App\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Component\Utility\Random;

/**
 * Team member fullname field type.
 *
 * @FieldType(
 *   id = "team_member_fullname",
 *   label = @Translation("Team Member Fullname"),
 *   description = @Translation("Concats firstname and lastname fields"),
 * )
 */
class TeamMemberFullnameField extends FieldItemBase {

  /**
   * Whether or not the value has been calculated.
   *
   * @var bool
   */
  protected bool $isCalculated = FALSE;

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {
    $label = new TranslatableMarkup('Team Member Fullname');
    $properties['value'] = DataDefinition::create('string')
      ->setLabel($label->__toString())
      ->setComputed(TRUE)
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition): array {
    $random = new Random();
    $values['value'] = ucwords($random->word(6)) . ' ' . ucwords($random->word(7));
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    $this->ensureCalculated();
    return parent::isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function __get($name) {
    $this->ensureCalculated();
    return parent::__get($name);
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    $this->ensureCalculated();
    return parent::getValue();
  }

  /**
   * Calculates the value of the field and sets it.
   */
  protected function ensureCalculated(): void {
    if (!$this->isCalculated) {
      $entity = $this->getEntity();
      if (!$entity->isNew()) {
        $this->setValue([
          'value' => $entity->get('firstname')->value . ' ' . $entity->get('lastname')->value,
        ]);
      }
      $this->isCalculated = TRUE;
    }
  }

}
