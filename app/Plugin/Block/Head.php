<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Service\Entity\NodeInterface;
use App\Service\Block\HeadServiceInterface;
use App\Service\Storage\NodeStorageInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The head block.
 *
 * @block(
 *  id = "app.head",
 *  admin_label = @Translation("Head Block"),
 * )
 */
class Head extends BlockBase implements ContainerFactoryPluginInterface {

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
   * Head service.
   *
   * @var \App\Service\Block\HeadServiceInterface
   */
  private HeadServiceInterface $headService;

  /**
   * Node storage.
   *
   * @var \App\Service\Storage\NodeStorageInterface
   */
  private NodeStorageInterface $nodeStorage;

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
     $container->get('app.block.head'),
     $container->get('current_route_match'),
     $container->get('current_user'),
     $container->get('entity_type.manager')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    HeadServiceInterface $head_service,
    RouteMatchInterface $route,
    AccountProxyInterface $current_user,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->headService = $head_service;
    $this->route = $route;
    $this->currentUser = $current_user;
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'head',
      '#node' => $this->getNodeByRoute(),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Gets the current node (if applicable) based on the current route.
   */
  private function getNodeByRoute(): ?NodeInterface {
    $route_name = $this->route->getRouteName();

    if ($route_name == 'entity.node.canonical') {
      return $this->getNodeByCanonicalRoute();
    }

    if ($route_name == 'entity.node.revision') {
      $this->messenger()->addStatus('Viewing in revision mode.');
      return $this->getNodeByRevisionRoute();
    }

    if ($route_name == 'entity.node.preview') {
      $this->messenger()->addStatus('Viewing in preview mode.');
      return $this->getNodeByPreviewRoute();
    }

    return NULL;
  }

  /**
   * Gets the current node by the canonical route.
   */
  private function getNodeByCanonicalRoute(): NodeInterface {
    $node = $this->route->getParameter('node');
    $this->appendEntityCacheTags($node);
    return $node;
  }

  /**
   * Get node revision route.
   */
  private function getNodeByRevisionRoute(): NodeInterface {
    $revision = $this->route->getParameter('node_revision');
    $node = $this->nodeStorage->loadRevision($revision);
    $this->messenger()->addStatus('Viewing in revision mode.');
    $this->disableCache();
    return $node;
  }

  /**
   * Get node preview route.
   */
  private function getNodeByPreviewRoute(): NodeInterface {
    $node = $this->route->getParameter('node_preview');
    $this->messenger()->addStatus('Viewing in preview mode.');
    $this->disableCache();
    return $node;
  }

}
