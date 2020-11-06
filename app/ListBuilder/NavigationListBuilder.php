<?php

declare(strict_types=1);

namespace App\ListBuilder;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for the navigation entity type.
 */
class NavigationListBuilder extends EntityListBuilder {

  /**
   * Current admin user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private AccountProxyInterface $currentUser;

  /**
   * Url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  private UrlGeneratorInterface $urlGenerator;

  /**
   * Constructs a new TeamMemberListBuilder object.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    EntityStorageInterface $storage,
    AccountProxyInterface $current_user,
    UrlGeneratorInterface $url_generator
  ) {
    parent::__construct($entity_type, $storage);
    $this->currentUser = $current_user;
    $this->urlGenerator = $url_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(
    ContainerInterface $container,
    EntityTypeInterface $entity_type
  ): self {
    return new self(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('current_user'),
      $container->get('url_generator')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityIds(): array {
    $query = $this->getStorage()->getQuery()
      ->sort($this->entityType->getKey('label'));

    return $query->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    $user_id = $this->currentUser->id();
    $build = [];

    if ((int) $user_id === 1) {
      $build['description'] = [
        '#markup' => $this->t('Navigations are fieldable entities. You can manage the fields on the <a href="@adminlink">Navigation admin page</a>.', [
          '@adminlink' => $this->urlGenerator->generateFromRoute('entity.navigation.settings'),
        ]),
      ];
    }

    $build += parent::render();

    $query = $this->getStorage()->getQuery()->count();
    $total = $query->execute();

    $build['summary']['#markup'] = $this->t('Total navigations: @total', ['@total' => $total]);
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['name'] = $entity->getName();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);

    if (!array_key_exists('edit', $operations)) {
      return $operations;
    }

    $operations['edit']['url'] = $entity->toUrl('edit-form');

    return $operations;
  }

}
