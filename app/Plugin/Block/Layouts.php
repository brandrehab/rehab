<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Service\Entity\NodeInterface;
use App\Traits\Block\RendersLayoutsTrait;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The layouts block.
 *
 * @Block(
 *  id = "app.layouts",
 *  admin_label = @Translation("Layouts Block"),
 * )
 */
class Layouts extends BlockBase implements ContainerFactoryPluginInterface {

  use RendersLayoutsTrait;

  /**
   * Renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected RendererInterface $renderer;

  /**
   * Block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected BlockManagerInterface $blockManager;

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
   * Dependecy injection.
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): self {
    return new self(
     $configuration,
     $plugin_id,
     $plugin_definition,
     $container->get('renderer'),
     $container->get('plugin.manager.block')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RendererInterface $renderer,
    BlockManagerInterface $block_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->renderer = $renderer;
    $this->blockManager = $block_manager;
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    $node = $this->getNode();

    return [
      '#theme' => 'layouts',
      '#render' => $this->processLayouts($node),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Get the current node.
   */
  private function getNode(): NodeInterface {
    $config = $this->getConfiguration();
    $node = $config['node'];
    $this->appendEntityCacheTags($node);
    return $node;
  }

}
