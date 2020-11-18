<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Entity\CompanyInterface;
use App\Storage\NavigationStorageInterface;
use App\Storage\CompanyStorageInterface;
use App\Service\Navigation\Link\LinkCollectionInterface;
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
   * Company entity id.
   *
   * @var int
   */
  const COMPANY_ENTITY_ID = 1;

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
   * Company storage.
   *
   * @var \App\Storage\CompanyStorageInterface
   */
  private CompanyStorageInterface $companyStorage;

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
    $this->companyStorage = $entity_type_manager->getStorage('company');
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'foot',
      '#reload' => $this->reloadEnabled(),
      '#menu' => $this->buildNavigation(),
      '#company' => $this->getCompany(),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Build the footer navigation.
   */
  private function buildNavigation(): LinkCollectionInterface {
    $navigation = $this->navigationStorage->getByName('footer');
    $menu = $navigation->getMenu();
    $this->appendEntityCacheTags($menu);
    return $navigation->build(self::NAV_MIN_DEPTH, self::NAV_MAX_DEPTH);
  }

  /**
   * Get the company details.
   */
  private function getCompany(): ?CompanyInterface {
    if (!$company = $this->companyStorage->load(self::COMPANY_ENTITY_ID)) {
      return NULL;
    };
    $this->appendEntityCacheTags($company);
    return $company;
  }

  /**
   * Should page reloading be enabled.
   */
  private function reloadEnabled(): bool {
    return getenv('DEVELOPMENT_SETTINGS') ? TRUE : FALSE;
  }

}
