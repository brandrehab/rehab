<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Storage\NavigationStorageInterface;
use Drupal\Core\Block\BlockBase;
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
   * Cache settings.
   *
   * @var array
   */
  private array $cache = [
    'contexts' => [
      'route',
    ],
    'tags' => [
      'config:system.menu.main',
    ],
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
      '#menu' => $this->navigationStorage->getByName('main')->build(1, 2),
      '#cache' => $this->cache,
    ];
  }

}
