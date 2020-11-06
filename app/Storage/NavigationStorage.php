<?php

declare(strict_types=1);

namespace App\Storage;

use App\Service\Navigation\NavigationBuilderInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Database\Connection;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the storage handler class for navigations.
 *
 * This extends the base storage class, adding required special handling for
 * navigations.
 */
class NavigationStorage extends SqlContentEntityStorage implements NavigationStorageInterface {

  /**
   * Navigation builder.
   *
   * @var \App\Service\Navigation\NavigationBuilderInterface
   */
  protected NavigationBuilderInterface $navigationBuilder;

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
      $container->get('app.navigation.builder')
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
    NavigationBuilderInterface $navigation_builder
  ) {
    $this->navigationBuilder = $navigation_builder;
    parent::__construct($entity_type, $database, $entity_field_manager, $cache, $language_manager, $memory_cache, $entity_type_bundle_info, $entity_type_manager);
  }

  /**
   * Get the navigation builder.
   */
  public function getNavigationBuilder(): NavigationBuilderInterface {
    return $this->navigationBuilder;
  }

  /**
   * Get a navigation by name.
   */
  public function getByName(string $navigation_name): ?EntityInterface {
    $query = $this->getQuery()
      ->condition('name', $navigation_name)
      ->range(0, 1);

    if (!$navigation_ids = $query->execute()) {
      return NULL;
    }

    return $this->load(array_pop($navigation_ids));
  }

  /**
   * Get a navigation by the associated menu name.
   */
  public function getByMenuId(string $menu_id): ?EntityInterface {
    $query = $this->getQuery()
      ->condition('menu', $menu_id)
      ->range(0, 1);

    if (!$navigation_ids = $query->execute()) {
      return NULL;
    }

    return $this->load(array_pop($navigation_ids));
  }

}
