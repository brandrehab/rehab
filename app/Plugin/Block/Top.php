<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Service\Entity\NodeInterface;
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
  private array $cache = [
    'contexts' => [
      'route',
    ],
    'tags' => [],
  ];

  /**
   * Build the render array.
   */
  public function build(): array {
    $node = $this->getNode();

    $this->cache['tags'] = [
      'node:' . $node->id(),
      'preview:' . $node->id(),
      'revision:' . $node->id(),
    ];

    return [
      '#theme' => 'top',
      '#heading' => $node->getHeading(),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Get the current node.
   */
  private function getNode(): NodeInterface {
    $config = $this->getConfiguration();
    return $config['node'];
  }

}
