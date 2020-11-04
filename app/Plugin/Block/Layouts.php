<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Block\BlockBase;
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
   * Renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  private RendererInterface $renderer;

  /**
   * Rendering of any template html.
   *
   * @var string|null
   */
  private ?string $rendering = NULL;

  /**
   * Block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  private BlockManagerInterface $blockManager;

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
    $config = $this->getConfiguration();
    $node = $config['node'];

    $this->cache['tags'] = [
      'node:' . $node->id(),
      'preview:' . $node->id(),
      'revision:' . $node->id(),
    ];

    if ($layouts = $node->getLayouts()) {
      $this->renderLayouts((int) $node->id(), $layouts);
    }

    return [
      '#theme' => 'layouts',
      '#render' => $this->rendering ?? NULL,
      '#cache' => $this->cache,
    ];
  }

  /**
   * Render any Layouts to html.
   */
  private function renderLayouts(int $node_id, array $layouts): void {
    for ($x = 0; $x < count($layouts); $x++) {
      $key = array_keys($layouts[$x])[0];
      $val = $layouts[$x][$key];

      switch ($key) {
        case 'text':
          $layout = $this->blockManager->createInstance('app.layouts.text', [
            'text' => $val,
          ])->build();
          break;

        default:
          $layout = NULL;
          break;
      }

      if ($layout) {
        $this->mergeCache($layout[0]['#cache']);
        $this->rendering .= $this->renderer->renderPlain($layout);
      }
    }
  }

  /**
   * Merge a template cache into the templator cache.
   */
  private function mergeCache(array $cache): void {
    $this->cache['contexts'] = array_unique(array_merge($this->cache['contexts'], $cache['contexts']));
    $this->cache['tags'] = array_unique(array_merge($this->cache['tags'], $cache['tags']));
  }

}
