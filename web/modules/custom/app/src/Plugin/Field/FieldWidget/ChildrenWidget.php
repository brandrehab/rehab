<?php

declare(strict_types=1);

namespace Drupal\app\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Children widget.
 *
 * @FieldWidget(
 *   id = "children_widget",
 *   label = @Translation("Children"),
 *   field_types = {
 *     "children_field"
 *   }
 * )
 */
class ChildrenWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  private $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings,
    array $third_party_settings,
    EntityTypeManager $entity_type_manager
  ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Create an instance.
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): self {
    return new self(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {

    $types = $this->entityTypeManager->getStorage('node_type')->loadMultiple();

    $options = [];

    foreach ($types as $type) {
      if ($type->id() == 'overview') {
        continue;
      }

      $options[$type->id()] = $type->get('name');
    }

    $element['value'] = $element + [
      '#type' => 'select',
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#options' => $options,
      '#empty_option' => '-- Please Select --',
    ];

    return $element;
  }

}
