<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MenuRepositoryInterface;
use App\EntityView\ServiceEntityView;
use App\Service\Entity\Node;
use App\Service\Entity\NodeInterface;
use App\Service\Entity\AssignsTitleFromSeoFieldTrait;
use App\Service\Entity\UpdatesChildMenuLinksTrait;
use App\Service\Entity\ControlsMenuLinkStatusTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Entity for content of type service.
 */
class Service extends Node implements ServiceInterface {

  use AssignsTitleFromSeoFieldTrait;
  use UpdatesChildMenuLinksTrait;
  use ControlsMenuLinkStatusTrait;

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected string $entityView = ServiceEntityView::class;

  /**
   * Menu factory.
   *
   * @var \App\Repository\MenuRepositoryInterface
   */
  protected MenuRepositoryInterface $menuRepository;

  /**
   * Image style storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  public EntityStorageInterface $imageStyle;

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
      $container->get('app.repository.menu'),
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
    MenuRepositoryInterface $menu_repository,
    array $values,
    string $entity_type,
    ?string $bundle,
    array $translations
  ) {
    $this->menuRepository = $menu_repository;
    $this->imageStyle = $entity_type_manager->getStorage('image_style');
    parent::__construct($values, $entity_type, $bundle, $translations);
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    $this->assignTitleFromSeoField();
    $this->updateChildMenuLinks($storage);
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE): void {
    parent::postSave($storage, $update);
    $this->controlMenuItemStatus();
  }

}
