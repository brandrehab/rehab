<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Storage\TimelineEventStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The layouts timeline block.
 *
 * @Block(
 *  id = "app.layouts.timeline",
 *  admin_label = @Translation("Layouts Timeline Block"),
 * )
 */
class LayoutsTimeline extends BlockBase implements ContainerFactoryPluginInterface {

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
   * Timeline event storage.
   *
   * @var \App\Storage\TimelineEventStorageInterface
   */
  private TimelineEventStorageInterface $timelineEventStorage;

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
    $this->timelineEventStorage = $entity_type_manager->getStorage('timeline_event');
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'layouts_timeline',
      '#events' => $this->getTimelineEvents(),
      '#grouping' => $this->getGrouping(),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Get the timeline events.
   */
  private function getTimelineEvents(): ?array {
    $this->setTimelineEventsListTag();
    $config = $this->getConfiguration();

    $order = $config['timeline']['order'];
    return $this->timelineEventStorage->getAll($order);
  }

  /**
   * Get how the timeline events should be grouped.
   */
  private function getGrouping(): string {
    $config = $this->getConfiguration();
    return $config['timeline']['group'];
  }

  /**
   * Set a list cache tag based on the timeline event entity.
   */
  private function setTimelineEventsListTag(): void {
    $timeline_event_type = $this->timelineEventStorage->getEntityType();
    $this->appendEntityTypeListCacheTags($timeline_event_type);
  }

}
