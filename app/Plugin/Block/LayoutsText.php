<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * The layouts text block.
 *
 * @Block(
 *  id = "app.layouts.text",
 *  admin_label = @Translation("Layouts Text Block"),
 * )
 */
class LayoutsText extends BlockBase {
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
    $config = $this->getConfiguration();
    $text = $config['text'];

    return [
      [
        '#theme' => 'layouts_text',
        '#text' => $text,
        '#cache' => $this->cache,
      ],
    ];
  }

}
