<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Error;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Error controller.
 */
class ErrorController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  private BlockManagerInterface $blockManager;

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
   * Handles requests for nodes of type error.
   */
  public function view(Error $node): array {
    return [
      $this->blockManager->createInstance('app.top', [
        'node' => $node,
      ])->build(),
      $this->blockManager->createInstance('app.layouts', [
        'node' => $node,
      ])->build(),
    ];
  }

}
