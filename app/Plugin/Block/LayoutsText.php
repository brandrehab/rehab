<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;

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
    return [
      '#theme' => 'layouts_text',
      '#text' => $this->getText(),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Get the text.
   */
  private function getText(): ?string {
    $config = $this->getConfiguration();
    return $config['text'];
  }

}
