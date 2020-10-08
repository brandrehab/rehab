<?php

declare(strict_types=1);

namespace Drupal\top\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * The top block.
 *
 * @block(
 *  id = "app.top",
 *  admin_label = @Translation("Top Block"),
 * )
 */
class Top extends BlockBase {

  /**
   * Cache settings.
   *
   * @var array
   */
  private $cache = [
    'contexts' => [
      'route',
    ],
    'tags' => [],
  ];

  /**
   * Build the render array.
   */
  public function build(): array {
    $config = $this->getConfiguration();
    $node = $config['node'];

    $this->cache['tags'] = [
      'node:' . $node->id(),
      'preview:' . $node->id(),
      'revision:' . $node->id(),
    ];

    return [
      '#theme' => 'top',
      '#heading' => $node->heading(),
      '#cache' => $this->cache,
    ];
  }

}
