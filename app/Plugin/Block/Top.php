<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Service\Entity\NodeInterface;

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
  protected array $cache = [
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

    return [
      [
        '#theme' => 'top',
        '#heading' => $node->getHeading(),
        '#cache' => $this->cache,
      ],
      [
        '#type' => 'status_messages',
      ],
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
