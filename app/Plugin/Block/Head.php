<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Service\Block\HeadServiceInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The head block.
 *
 * @block(
 *  id = "app.head",
 *  admin_label = @Translation("Head Block"),
 * )
 */
class Head extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Cache settings.
   *
   * @var array
   */
  private array $cache = [
    'contexts' => [
      'route',
    ],
    'tags' => [],
  ];

  /**
   * Head service.
   *
   * @var \App\Service\Block\HeadServiceInterface
   */
  private HeadServiceInterface $headService;

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
     $container->get('app.block.head')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    HeadServiceInterface $head_service
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->headService = $head_service;
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'head',
      '#cache' => $this->cache,
    ];
  }

}
