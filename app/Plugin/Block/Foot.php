<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Storage\NavigationStorageInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The foot block.
 *
 * @block(
 *  id = "app.foot",
 *  admin_label = @Translation("Foot Block"),
 * )
 */
class Foot extends BlockBase implements ContainerFactoryPluginInterface {

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
      'config:system.menu.footer',
    ],
  ];

  /**
   * Navigation storage.
   *
   * @var \App\Storage\NavigationStorageInterface
   */
  private NavigationStorageInterface $navigationStorage;

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
      '#theme' => 'foot',
      '#reload' => getenv('DEVELOPMENT_SETTINGS') ? TRUE : FALSE,
      '#menu' => $this->navigationStorage->getByName('footer')->build(1, 2),
      '#cache' => $this->cache,
    ];
  }

}
