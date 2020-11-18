<?php

declare(strict_types=1);

namespace App\Base;

use Drupal\Core\Block\BlockBase as Block;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Cache\Cache;

/**
 * A base class for plugin blocks.
 */
abstract class BlockBase extends Block {

  /**
   * Cache settings.
   *
   * @var array
   */
  protected array $cache = [
    'contexts' => [],
    'tags' => [],
  ];

  /**
   * Appends the cache tags of a cacheable entity to the cache render array.
   */
  protected function appendEntityCacheTags(EntityInterface $entity): void {
    $this->cache['tags'] = Cache::mergeTags(
      $this->cache['tags'],
      $entity->getCacheTags()
    );
  }

  /**
   * Disables the render array cache.
   */
  protected function disableCache(): void {
    $this->cache['max-age'] = 0;
  }

}
