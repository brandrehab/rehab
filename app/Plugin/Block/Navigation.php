<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Storage\NavigationStorageInterface;
use App\Service\Navigation\Link\LinkCollectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The navigation block.
 *
 * @Block(
 *  id = "app.navigation",
 *  admin_label = @Translation("Navigation Block"),
 * )
 */
class Navigation extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Minimum depth of heirarchical links.
   *
   * @var int
   */
  const NAV_MIN_DEPTH = 1;

  /**
   * Maximum depth of heirarchical links.
   *
   * @var int
   */
  const NAV_MAX_DEPTH = 2;

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
   * Navigation storage.
   *
   * @var \App\Storage\NavigationStorageInterface
   */
  private NavigationStorageInterface $navigationStorage;

  /**
   * Manage class dependency injection.
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
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->navigationStorage = $entity_type_manager->getStorage('navigation');
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'navigation',
      '#menu' => $this->buildNavigation(),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Build the main navigation.
   */
  private function buildNavigation(): LinkCollectionInterface {
    $navigation = $this->navigationStorage->getByName('main');
    $menu = $navigation->getMenu();
    $this->appendEntityCacheTags($menu);
    return $navigation->build(self::NAV_MIN_DEPTH, self::NAV_MAX_DEPTH);
  }

}
