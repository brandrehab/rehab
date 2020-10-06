<?php

declare(strict_types=1);

namespace Drupal\app\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The toolbar block.
 *
 * @block(
 *  id = "app.toolbar",
 *  admin_label = @Translation("Frontend Toolbar Block"),
 * )
 */
class Toolbar extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Active node Id.
   *
   * @var string|null
   */
  private $id;

  /**
   * Preview node uuid.
   *
   * @var string|null
   */
  private $uuid;

  /**
   * Revision Id.
   *
   * @var string|null
   */
  private $vid;

  /**
   * Node type.
   *
   * @var string|null
   */
  private $bundle;

  /**
   * Route.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  private $route;

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
   * Dependency Injection.
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
     $container->get('current_route_match')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteMatchInterface $route
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->route = $route;
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    $route_name = $this->route->getRouteName();

    switch ($route_name) {
      case 'entity.node.revision':
        $this->revision();
        break;

      case 'entity.node.canonical':
        $this->canonical();
        break;

      case 'entity.node.preview':
        $this->preview();
        break;
    }

    $this->cache['tags'] = [
      'node:' . $this->id,
      'preview:' . $this->id,
      'revision:' . $this->vid,
    ];

    return [
      [
        '#theme' => 'toolbar',
        '#id' => $this->id,
        '#uuid' => $this->uuid,
        '#bundle' => $this->bundle,
        '#cache' => $this->cache,
      ],
    ];
  }

  /**
   * Prepare params where the node entity is canonical.
   */
  private function canonical(): void {
    $this->id = $this->route->getRawParameter('node');
  }

  /**
   * Prepare params where the node entity is a revision.
   */
  private function revision(): void {
    $this->id = $this->route->getRawParameter('node');
    $this->vid = $this->route->getRawParameter('node_revision');
    $this->messenger()->addStatus('Viewing in Page Revision mode.');
  }

  /**
   * Prepare params where the node entity is a preview.
   */
  private function preview(): void {
    $node = $this->route->getParameter('node_preview');
    $this->id = $node->id();
    $this->uuid = $node->uuid();
    $this->bundle = $node->bundle();
    $this->messenger()->addStatus("Viewing in Page Preview mode.");
  }

}
