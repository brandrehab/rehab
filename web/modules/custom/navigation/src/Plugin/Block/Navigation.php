<?php

declare(strict_types=1);

namespace Drupal\navigation\Plugin\Block;

use App\Repository\MenuRepositoryInterface;
use Drupal\Core\Block\BlockBase;
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
   * Cache settings.
   *
   * @var array
   */
  private $cache = [
    'contexts' => [
      'route',
    ],
    'tags' => [
      'config:system.menu.main',
    ],
  ];

  /**
   * Menu service.
   *
   * @var \App\Service\Menu\MenuInterface
   */
  private $mainMenu;

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
      $container->get('app.repository.menu')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MenuRepositoryInterface $menu_repository
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->mainMenu = $menu_repository->get('main');
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'navigation',
      '#menu' => $this->mainMenu->build(1, 2),
      '#cache' => $this->cache,
    ];
  }

}
