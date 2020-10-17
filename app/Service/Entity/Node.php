<?php

declare(strict_types=1);

namespace App\Service\Entity;

use App\Service\EntityView\EntityViewInterface;
use Drupal\node\Entity\Node as NodeBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base entity for nodes of all types.
 */
class Node extends NodeBase implements NodeInterface {

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
