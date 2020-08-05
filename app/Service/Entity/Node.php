<?php

declare(strict_types=1);

namespace App\Service\Entity;

use App\Service\EntityView\EntityViewInterface;
use Drupal\node\Entity\Node as NodeBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Node entity.
 */
class Node extends NodeBase implements NodeInterface {

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
   * These are the fields we care about.
   */
  public function entityView(): EntityViewInterface {
    $entity_view_classname = $this->entityView;
    return new $entity_view_classname($this);
  }

}
