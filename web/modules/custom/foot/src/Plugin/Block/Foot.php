<?php

declare(strict_types=1);

namespace Drupal\foot\Plugin\Block;

use App\Repository\MenuRepositoryInterface;
use Drupal\Core\Block\BlockBase;
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
  private $cache = [
    'contexts' => [
      'route',
    ],
    'tags' => [
      'config:system.menu.footer',
    ],
  ];

  /**
   * Menu service.
   *
   * @var \App\Service\Menu\MenuInterface
   */
  private $footerMenu;

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
    $this->footerMenu = $menu_repository->get('footer');
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'foot',
      '#reload' => getenv('DEVELOPMENT_SETTINGS') ? TRUE : FALSE,
      '#menu' => $this->footerMenu->build(1, 2),
      '#cache' => $this->cache,
    ];
  }

}
