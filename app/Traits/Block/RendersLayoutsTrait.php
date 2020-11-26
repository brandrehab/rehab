<?php

declare(strict_types=1);

namespace App\Traits\Block;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Render\RendererInterface;

/**
 * Processes layout data providing both rendered output and cache tags/context.
 */
trait RendersLayoutsTrait {

  use BuildsTextLayoutTrait;
  use BuildsTeamLayoutTrait;
  use BuildsTimelineLayoutTrait;
  use BuildsListingsLayoutTrait;

  /**
   * Specify the available builder traits.
   *
   * @var array
   */
  private array $layoutBuilders = [
    'text' => 'getTextLayout',
    'team' => 'getTeamLayout',
    'timeline' => 'getTimelineLayout',
    'listings' => 'getListingsLayout',
  ];

  /**
   * Rendering of any template html.
   *
   * @var string|null
   */
  private ?string $layoutRendering = NULL;

  /**
   * Renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected RendererInterface $renderer;

  /**
   * Process the layouts.
   */
  protected function processLayouts(): ?string {
    if (!$layouts = $this->currentNode->getLayouts()) {
      return NULL;
    }
    return $this->renderLayouts($layouts);
  }

  /**
   * Render any Layouts to html.
   */
  private function renderLayouts(array $layouts): ?string {
    foreach ($layouts as $layout) {
      $name = array_keys($layout)[0];
      if (!$render_array = $this->renderLayout($name, $layout[$name])) {
        continue;
      }
      $this->mergeCacheWithLayout($render_array['#cache']);
      $this->appendToRendering($render_array);
    }
    return $this->layoutRendering;
  }

  /**
   * Prepare a render array from the specified layout.
   *
   * @param string $layout_name
   *   Name of the layout to render.
   * @param mixed $data
   *   Data associated with the layout.
   */
  private function renderLayout(string $layout_name, $data): ?array {
    if (!array_key_exists($layout_name, $this->layoutBuilders)) {
      return NULL;
    }
    $builder = $this->layoutBuilders[$layout_name];
    $layout = $this->{$builder}($data);
    return $layout ?? NULL;
  }

  /**
   * Render an array and append it to the rendering property.
   */
  private function appendToRendering(array $render_array): void {
    $this->layoutRendering .= $this->renderer->renderPlain($render_array);
  }

  /**
   * Merge a layout cache into the block cache.
   */
  private function mergeCacheWithLayout(array $cache): void {
    $this->cache['contexts'] = Cache::mergeContexts(
      $this->cache['contexts'],
      $cache['contexts']
    );
    $this->cache['tags'] = Cache::mergeTags(
      $this->cache['tags'],
      $cache['tags']
    );
  }

}
