<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Storage\NavigationStorageInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The toolbar block.
 *
 * @block(
 *  id = "app.admin.toolbar",
 *  admin_label = @Translation("Admin Toolbar Block"),
 * )
 */
class AdminToolbar extends BlockBase implements ContainerFactoryPluginInterface {
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
  private array $cache = [
    'contexts' => [
      'user.roles',
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
    AccountProxyInterface $current_user,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->navigationStorage = $entity_type_manager->getStorage('navigation');
    $this->currentUser = $current_user;
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    if ($this->currentUser->id() == 1) {
      $toolbar = $this->navigationStorage->getByName('admin')->build(1, 2);
      $this->cache['tags'][] = 'config:system.menu.admin-toolbar';
    }
    else {
      $toolbar = $this->navigationStorage->getByName('client')->build(1, 2);
      $this->cache['tags'][] = 'config:system.menu.client-admin-toolbar';
    }

    return [
      [
        '#theme' => 'admin_toolbar',
        '#menu' => $toolbar,
        '#cache' => $this->cache,
        '#attached' => [
          'library' => [
            'app/admin-toolbar',
          ],
          'drupalSettings' => [],
        ],
      ],
    ];
  }

}
