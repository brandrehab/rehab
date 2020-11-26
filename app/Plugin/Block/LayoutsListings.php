<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Entity\OverviewInterface;
use App\Storage\NodeStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The layouts listings block.
 *
 * @Block(
 *  id = "app.layouts.listings",
 *  admin_label = @Translation("Layouts Listings Block"),
 * )
 */
class LayoutsListings extends BlockBase implements ContainerFactoryPluginInterface {

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
   * Node storage.
   *
   * @var \App\Storage\NodeStorageInterface
   */
  private NodeStorageInterface $nodeStorage;

  /**
   * Overview node.
   *
   * @var \App\Entity\OverviewInterface
   */
  protected OverviewInterface $currentOverview;

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
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    $this->currentOverview = $this->getCurrentOverview();

    return [
      '#theme' => 'layouts_listings_' . $this->getGrid(),
      '#links' => $this->getLinks(),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Get the child links.
   */
  private function getLinks(): ?array {
    $bundle = $this->currentOverview->getChildType();
    return $this->nodeStorage->getLinks($bundle);
  }

  /**
   * Get the grid arrangement to be used for links.
   */
  private function getGrid(): string {
    $options = $this->getListingsOptions();
    return $options['grid'];
  }

  /**
   * Get the current overview from the block configuration.
   */
  private function getCurrentOverview(): OverviewInterface {
    $config = $this->getConfiguration();
    return $config['node'];
  }

  /**
   * Get the listings options from the block configuration.
   */
  private function getListingsOptions(): array {
    $config = $this->getConfiguration();
    return $config['listings'];
  }

}
