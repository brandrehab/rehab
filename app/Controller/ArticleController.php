<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ArticleInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Article controller.
 */
class ArticleController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  private $blockManager;

  /**
   * Dependency injection.
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('plugin.manager.block')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(BlockManagerInterface $block_manager) {
    $this->blockManager = $block_manager;
  }

  /**
   * Handles requests for nodes of type article.
   */
  public function view(ArticleInterface $node): array {
    $view = $node->entityView()->get('full');
    return [
      $this->blockManager->createInstance('app.top', [
        'node' => $view,
      ])->build(),
    ];
  }

}
