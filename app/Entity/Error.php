<?php

declare(strict_types=1);

namespace App\Entity;

use App\EntityView\ErrorEntityView;
use App\Service\Entity\Node;
use App\Service\Entity\NodeInterface;
use App\Service\Entity\AssignsTitleFromSeoFieldTrait;
use App\Service\Entity\ControlsMenuLinkStatusTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Entity for content of type error.
 */
class Error extends Node implements ErrorInterface {

  use AssignsTitleFromSeoFieldTrait;
  use ControlsMenuLinkStatusTrait;

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = ErrorEntityView::class;

  /**
   * Image style storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  public $imageStyle;

  /**
   * Manage class dependency injection.
   */
  public static function createInstance(
    ContainerInterface $container,
    array $values,
    string $entity_type,
    ?string $bundle = NULL,
    array $translations = []
  ): NodeInterface {
    return new self(
      $container->get('entity_type.manager'),
      $values,
      $entity_type,
      $bundle,
      $translations
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    array $values,
    string $entity_type,
    ?string $bundle,
    array $translations
  ) {
    $this->imageStyle = $entity_type_manager->getStorage('image_style');
    parent::__construct($values, $entity_type, $bundle, $translations);
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    $this->assignTitleFromSeoField();
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE): void {
    parent::postSave($storage, $update);
    $this->controlMenuItemStatus();
  }

}
