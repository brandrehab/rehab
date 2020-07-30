<?php

declare(strict_types=1);

namespace Drupal\app\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Children formatter.
 *
 * @FieldFormatter(
 *   id = "children_formatter",
 *   label = @Translation("Children"),
 *   field_types = {
 *     "children_field"
 *   }
 * )
 */
class ChildrenFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => $this->viewValue($item)];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   */
  protected function viewValue(FieldItemInterface $item): string {
    return nl2br(Html::escape($item->value));
  }

}
