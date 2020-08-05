<?php

declare(strict_types=1);

namespace App\Entity;

use App\EntityView\HomeEntityView;
use App\Service\Entity\Node;
use App\Service\Entity\NodeInterface;
use App\Service\Entity\AssignsTitleFromSeoFieldTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Home entity.
 */
class Home extends Node implements HomeInterface {

  use AssignsTitleFromSeoFieldTrait;

  /**
   * Associate an entity view with this entity.
   *
   * @var string
   */
  protected $entityView = HomeEntityView::class;

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
      $values,
      $entity_type,
      $bundle,
      $translations
    );
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    $this->assignTitleFromSeoField();
  }

}
