<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\PageInterface;
use App\Service\Storage\NodeStorage as BaseStorage;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extends base node storage to assign entities base on node type.
 */
class NodeStorage extends BaseStorage implements NodeStorageInterface {

  /**
   * Node entity types (bundles) and associated classes.
   *
   * @var array
   */
  static protected array $nodeEntityTypes = [];

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(
    ContainerInterface $container,
    EntityTypeInterface $entity_type
  ): self {
    return new self(
      $entity_type,
      $container->get('database'),
      $container->get('entity_field.manager'),
      $container->get('cache.entity'),
      $container->get('language_manager'),
      $container->get('entity.memory_cache'),
      $container->get('entity_type.bundle.info'),
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * Constructs a SqlContentEntityStorage object.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    Connection $database,
    EntityFieldManagerInterface $entity_field_manager,
    CacheBackendInterface $cache,
    LanguageManagerInterface $language_manager,
    MemoryCacheInterface $memory_cache,
    EntityTypeBundleInfoInterface $entity_type_bundle_info,
    EntityTypeManagerInterface $entity_type_manager,
    AccountProxyInterface $current_user
  ) {
    $this->currentUser = $current_user;
    parent::__construct($entity_type, $database, $entity_field_manager, $cache, $language_manager, $memory_cache, $entity_type_bundle_info, $entity_type_manager);

    if (!self::$nodeEntityTypes) {
      $this->setNodeEntityTypes();
    }
  }

  /**
   * Get page by title.
   */
  public function getPageByTitle(string $title): ?PageInterface {
    $query = $this->getBundleQuery('page')
      ->condition('title', $title)
      ->range(0, 1);

    if (!$pages = $query->execute()) {
      return NULL;
    }

    return $this->load(array_pop($pages));
  }

  /**
   * Get links.
   */
  public function getLinks(string $bundle): ?array {
    switch ($bundle) {
      case 'article':
        return $this->getArticleLinks();

      case 'service':
        return $this->getServiceLinks();

      default:
        return NULL;
    }
  }

  /**
   * Get article links.
   */
  public function getArticleLinks(): ?array {
    $query = $this->getBundleQuery('article');

    if (!$nodes = $query->execute()) {
      return NULL;
    }

    return $this->loadMultiple($nodes);
  }

  /**
   * Get service links.
   */
  public function getServiceLinks(): ?array {
    $query = $this->getBundleQuery('service');

    if (!$nodes = $query->execute()) {
      return NULL;
    }

    return $this->loadMultiple($nodes);
  }

}
