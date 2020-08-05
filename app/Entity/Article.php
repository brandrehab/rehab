<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MenuRepositoryInterface;
use App\EntityView\ArticleEntityView;
use App\Service\Entity\Node;
use App\Service\Entity\NodeInterface;
use App\Service\Entity\AssignsTitleFromSeoFieldTrait;
use App\Service\Entity\UpdatesChildMenuLinksTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Article entity.
 */
class Article extends Node implements ArticleInterface {

  use AssignsTitleFromSeoFieldTrait;
  use UpdatesChildMenuLinksTrait;

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = ArticleEntityView::class;

  /**
   * Menu factory.
   *
   * @var \App\Repository\MenuRepositoryInterface
   */
  protected $menuRepository;

  /**
   * Manage class dependency injection.
   */
  public static function createInstance(
    ContainerInterface $container,
    array $values,
    $entity_type,
    $bundle = FALSE,
    $translations = []
  ): NodeInterface {
    return new self(
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
    MenuRepositoryInterface $menu_repository,
    array $values,
    $entity_type,
    $bundle,
    $translations
  ) {
    $this->menuRepository = $menu_repository;
    parent::__construct($values, $entity_type, $bundle, $translations);
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    $this->assignTitleFromSeoField();
    $this->updateChildMenuLinks($storage);
  }

}
