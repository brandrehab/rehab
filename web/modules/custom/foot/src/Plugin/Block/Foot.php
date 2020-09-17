<?php

declare(strict_types=1);

namespace Drupal\foot\Plugin\Block;

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
    'tags' => [],
  ];

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
     $plugin_definition
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'foot',
      '#reload' => getenv('DEVELOPMENT_SETTINGS') ? TRUE : FALSE,
      '#cache' => $this->cache,
    ];
  }

}
