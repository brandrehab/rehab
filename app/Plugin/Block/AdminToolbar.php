<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Service\Navigation\Link\LinkCollectionInterface;
use App\Storage\NavigationStorageInterface;
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
   * Id of the admin superuser.
   *
   * @var int
   */
  const SUPERUSER_ID = 1;

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
    return [
      [
        '#theme' => 'admin_toolbar',
        '#menu' => $this->getNavigation(),
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

  /**
   * Gets the appropriate navigation based upon current user type.
   */
  private function getNavigation(): LinkCollectionInterface {
    if ($this->currentUser->id() == self::SUPERUSER_ID) {
      return $this->getAdminToolbar();
    }
    return $this->getClientToolbar();
  }

  /**
   * Gets the admin toolbar.
   */
  private function getAdminToolbar(): LinkCollectionInterface {
    $navigation = $this->navigationStorage->getByName('admin');
    $menu = $navigation->getMenu();
    $this->appendEntityCacheTags($menu);
    return $navigation->build(self::NAV_MIN_DEPTH, self::NAV_MAX_DEPTH);
  }

  /**
   * Gets the client toolbar.
   */
  private function getClientToolbar(): LinkCollectionInterface {
    $navigation = $this->navigationStorage->getByName('client');
    $menu = $navigation->getMenu();
    $this->appendEntityCacheTags($menu);
    return $navigation->build(self::NAV_MIN_DEPTH, self::NAV_MAX_DEPTH);
  }

}
