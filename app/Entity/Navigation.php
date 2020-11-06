<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Entity\AccessesEntityStorageTrait;
use App\Service\Navigation\NavigationBuilderInterface;
use App\Service\Navigation\Link\LinkCollectionInterface;
use Drupal\system\Entity\Menu;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the navigation entity class.
 *
 * @ContentEntityType(
 *   id = "navigation",
 *   provider = "app",
 *   label = @Translation("Navigation"),
 *   label_collection = @Translation("Navigations"),
 *   handlers = {
 *     "storage" = "App\Storage\NavigationStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "App\ListBuilder\NavigationListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "App\Form\Navigation\EditForm",
 *       "edit" = "App\Form\Navigation\EditForm",
 *       "delete" = "App\Form\Navigation\DeleteForm"
 *     },
 *     "access" = "App\AccessControlHandler\NavigationAccessControlHandler",
 *   },
 *   base_table = "navigation",
 *   admin_permission = "administer navigation",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "menu" = "menu",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/navigation/add",
 *     "edit-form" = "/admin/content/navigation/{navigation}/edit",
 *     "delete-form" = "/admin/content/navigation/{navigation}/delete",
 *     "collection" = "/admin/content/navigation"
 *   },
 *   field_ui_base_route = "entity.navigation.settings"
 * )
 */
class Navigation extends ContentEntityBase implements NavigationInterface {

  use EntityChangedTrait;
  use AccessesEntityStorageTrait;

  /**
   * Tree transformers.
   *
   * @var array
   */
  protected array $transformations = [
    ['callable' => 'menu.default_tree_manipulators:checkNodeAccess'],
    ['callable' => 'menu.default_tree_manipulators:checkAccess'],
    ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
  ];

  /**
   * Gets the navigation creation timestamp.
   */
  public function getCreatedTime(): string {
    return $this->get('created')->value;
  }

  /**
   * Gets the name of the navigation.
   */
  public function getName(): string {
    return $this->get('name')->value;
  }

  /**
   * Gets the menu associated with this navigation.
   */
  protected function getMenu(): Menu {
    return $this->get('menu')->first()->entity;
  }

  /**
   * Gets the navigation builder.
   */
  protected function getBuilder(): NavigationBuilderInterface {
    return $this->getStorage()->getNavigationBuilder();
  }

  /**
   * Gets a built navigation.
   */
  public function build(?int $min_depth = 1, ?int $max_depth = 1, ?string $root = ''): LinkCollectionInterface {
    return $this->getBuilder()->build(
      $this->getMenu()->id(),
      $min_depth,
      $max_depth,
      $root
    );
  }

  /**
   * Gets nids from a navigation as a one-dimensional array.
   */
  public function nids(?int $min_depth = 1, ?int $max_depth = 1, ?string $root = ''): array {
    $links = $this->build($min_depth, $max_depth, $root);
    return $this->getBuilder()->getNids($links);
  }

  /**
   * Sets the navigation creation timestamp.
   */
  public function setCreatedTime(string $timestamp): NavigationInterface {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name')->__toString())
      ->setDescription(t('Provide a unique name for the navigation.')->__toString())
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setRequired(TRUE)
      ->addConstraint('UniqueField', [])
      ->setTranslatable(FALSE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['menu'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Menu')->__toString())
      ->setDescription(t('Menu to associate with this navigation.')->__toString())
      ->setSetting('target_type', 'menu')
      ->setSetting('handler', 'default')
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'list_default',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on')->__toString())
      ->setDescription(t('The time that the navigation was created.')->__toString());

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed')->__toString())
      ->setDescription(t('The time that the navigation was last edited.')->__toString());

    return $fields;
  }

}
