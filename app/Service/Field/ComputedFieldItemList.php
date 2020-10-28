<?php

namespace App\Service\Field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataInterface;

/**
 * Item list for a computed field.
 */
class ComputedFieldItemList extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * Numerically indexed array of field items.
   *
   * @var \Drupal\Core\TypedData\TypedDataInterface[]
   */
  protected $list = [];

  /**
   * Constructs a TypedData object given its definition and context.
   */
  public function __construct(
    DataDefinitionInterface $definition,
    ?string $name = NULL,
    TypedDataInterface $parent = NULL
  ) {
    parent::__construct($definition, $name, $parent);
    $this->computeValue();
  }

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {
    $this->ensurePopulated();
  }

  /**
   * Computes the calculated values for this item list.
   */
  protected function ensurePopulated() {
    if (!isset($this->list[0])) {
      $this->list[0] = $this->createItem(0);
    }
  }

}
