<?php

declare(strict_types=1);

namespace App\Service\EntityView;

use Drupal\node\NodeInterface;

/**
 * NOde entity view.
 */
abstract class NodeEntityView extends EntityView implements NodeEntityViewInterface {

  /**
   * View definitions.
   *
   * @var array
   */
  protected $definitions = [
    'full' => [
      'id',
      'preview',
      'title',
      'url',
      'created',
      'updated',
    ],
  ];

  /**
   * Entity.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $entity;

  /**
   * Class constructor.
   */
  public function __construct(NodeInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * Get the entity id.
   */
  protected function id(): int {
    return (int) $this->entity->id();
  }

  /**
   * Get the entity preview.
   */
  protected function preview(): bool {
    return $this->entity->is_preview == TRUE ? TRUE : FALSE;
  }

  /**
   * Get the entity title.
   */
  protected function title(): string {
    return $this->entity->title->value;
  }

  /**
   * Get the entity url.
   */
  protected function url(): string {
    if ($this->entity->getType() == 'home') {
      return '/';
    }
    return $this->entity->toUrl()->toString();
  }

  /**
   * Get entity created timestamp.
   */
  protected function created(): int {
    return (int) $this->entity->created->value;
  }

  /**
   * Get entity updated timestamp.
   */
  protected function updated(): int {
    return (int) $this->entity->created->value;
  }

}
