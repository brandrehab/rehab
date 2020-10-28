<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Service\Menu\MenuInterface;
use Drupal\Core\Block\BlockBase;
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
  private $currentUser;

  /**
   * Cache settings.
   *
   * @var array
   */
  private $cache = [
    'contexts' => [
      'user.roles',
    ],
    'tags' => [],
  ];

  /**
   * Client admin toolbar menu service.
   *
   * @var \App\Service\Menu\MenuInterface
   */
  private $clientAdminToolbar;

  /**
   * Admin toolbar menu service.
   *
   * @var \App\Service\Menu\MenuInterface
   */
  private $adminToolbar;

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
     $container->get('app.menu.client_admin_toolbar'),
     $container->get('app.menu.admin_toolbar')
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
    MenuInterface $client_admin_toolbar,
    MenuInterface $admin_toolbar
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->clientAdminToolbar = $client_admin_toolbar;
    $this->adminToolbar = $admin_toolbar;
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    $toolbar = $this->currentUser->id() == 1 ? $this->adminToolbar->build(1, 2) : $this->clientAdminToolbar->build(1, 2);
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
