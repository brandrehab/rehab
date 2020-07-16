<?php

declare(strict_types=1);

namespace App\Service\EntityView;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Entity view.
 */
abstract class EntityView implements EntityViewInterface {

  /**
   * Dto class.
   *
   * @var string
   */
  public $entityViewDto;

  /**
   * View definitions.
   *
   * @var array
   */
  protected $definitions = [
    'full' => [],
  ];

  /**
   * Entity.
   *
   * @var \Drupal\Core\Entity\ContentEntityInterface
   */
  protected $entity;

  /**
   * Class constructor.
   */
  public function __construct(ContentEntityInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * Get view.
   */
  public function get(string $definition = 'full'): EntityViewDtoInterface {
    $keys = $this->definitions[$definition];
    $view = [];
    foreach ($keys as $key) {
      $view[$key] = $this->$key();
    }
    return new $this->entityViewDto($view);
  }

}
