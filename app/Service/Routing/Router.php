<?php

declare(strict_types=1);

namespace App\Service\Routing;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\StackedRouteMatchInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Router.
 */
class Router implements ContainerInjectionInterface {
  /**
   * Route.
   *
   * @var \Drupal\Core\Routing\StackedRouteMatchInterface
   */
  private $route;

  /**
   * Node storage.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Instance of the container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  static private $container;

  /**
   * Class constructor.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    StackedRouteMatchInterface $route
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->route = $route;
  }

  /**
   * Dependency injection.
   */
  public static function create(ContainerInterface $container): self {
    self::$container = $container;
    return new self(
      $container->get('entity_type.manager'),
      $container->get('current_route_match')
    );
  }

  /**
   * Process node entity preview requests (ie. from cms generated pages).
   */
  public function preview(): array {
    $node = $this->route->getParameter('node_preview');
    return $this->view($node);
  }

  /**
   * Process node entity revision requests (ie. from cms generated pages).
   */
  public function revision(): array {
    $revision_id = $this->route->getParameter('node_revision');
    $node = $this->entityTypeManager->getStorage('node')->loadRevision($revision_id);
    return $this->view($node);
  }

  /**
   * Process node entity page requests (ie. from cms generated pages).
   */
  public function view(EntityInterface $node): array {
    $class_name = str_replace('_', '', ucwords($node->getType(), '_'));
    $namespaced_class = 'App\\Controller\\' . $class_name . 'Controller';
    if (class_exists($namespaced_class)) {
      $controller = $namespaced_class::create(self::$container);
      return $controller->view($node->entityView()->get('full'));
    }

    throw new NotFoundHttpException();
  }

}
