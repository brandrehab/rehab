<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The toolbar block.
 *
 * @block(
 *  id = "app.toolbar",
 *  admin_label = @Translation("Frontend Toolbar Block")
 * )
 */
class Toolbar extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Route.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  private RouteMatchInterface $route;

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private AccountProxyInterface $currentUser;

  /**
   * Cache settings.
   *
   * @var array
   */
  protected array $cache = [
    'contexts' => [
      'route',
      'user.roles',
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
     $container->get('current_route_match'),
     $container->get('current_user')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteMatchInterface $route,
    AccountProxyInterface $current_user
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->route = $route;
    $this->currentUser = $current_user;
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      [
        '#theme' => 'toolbar',
        '#route' => $this->getRoute(),
        '#cache' => $this->cache,
      ],
    ];
  }

  /**
   * Gets the node type and ids associated with the current route.
   */
  private function getRoute(): ?array {
    $route_name = $this->route->getRouteName();

    if ($route_name == 'entity.node.canonical') {
      return $this->getNodeCanonicalRoute();
    }

    if ($route_name == 'entity.node.revision') {
      return $this->getNodeRevisionRoute();
    }

    if ($route_name == 'entity.node.preview') {
      return $this->getNodePreviewRoute();
    }

    return NULL;
  }

  /**
   * Get node canonical route.
   */
  private function getNodeCanonicalRoute(): array {
    $node = $this->route->getParameter('node');
    $this->appendEntityCacheTags($node);
    $permission = 'create ' . $node->bundle() . ' content';
    if ($this->currentUser->hasPermission($permission)) {
      $bundle = $node->bundle();
    }
    return [
      'type' => 'node',
      'id' => $node->id(),
      'bundle' => $bundle ?? NULL,
    ];
  }

  /**
   * Get node revision route.
   */
  private function getNodeRevisionRoute(): array {
    $this->messenger()->addStatus('Viewing in revision mode.');
    $this->disableCache();
    return [
      'type' => 'revision',
      'id' => $this->route->getRawParameter('node'),
      'revision' => $this->route->getRawParameter('node_revision'),
    ];
  }

  /**
   * Get node preview route.
   */
  private function getNodePreviewRoute(): array {
    $this->messenger()->addStatus('Viewing in preview mode.');
    $node = $this->route->getParameter('node_preview');
    $this->disableCache();
    return [
      'type' => 'preview',
      'id' => $node->id(),
      'uuid' => $node->uuid(),
    ];
  }

}
