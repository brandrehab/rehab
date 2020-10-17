<?php

declare(strict_types=1);

namespace App\Service\EntityView;

use Drupal\node\NodeInterface;

/**
 * Node entity view.
 */
abstract class NodeEntityView extends EntityView implements NodeEntityViewInterface {

  /**
   * View definitions.
   *
   * @var array
   */
  protected $definitions = [
    'full' => [
      'id',
      'preview',
      'title',
      'heading',
      'url',
      'shorturl',
      'layouts',
      'created',
      'updated',
      'seo',
      'social',
    ],
  ];

  /**
   * Entity.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $entity;

  /**
   * Class constructor.
   */
  public function __construct(NodeInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * Get the entity preview.
   */
  protected function preview(): bool {
    return $this->entity->is_preview == TRUE ? TRUE : FALSE;
  }

  /**
   * Get the entity title.
   */
  protected function title(): string {
    return $this->entity->title->value;
  }

  /**
   * Get entity heading.
   */
  protected function heading(): string {
    return $this->entity->field_heading->value;
  }

  /**
   * Get the entity url.
   */
  protected function url(): string {
    if ($this->entity->getType() == 'home') {
      return '/';
    }
    return $this->entity->toUrl()->toString();
  }

  /**
   * Get the short url.
   */
  protected function shorturl(): string {
    return '/node/' . $this->id();
  }

  /**
   * Get entity created timestamp.
   */
  protected function created(): int {
    return (int) $this->entity->created->value;
  }

  /**
   * Get entity updated timestamp.
   */
  protected function updated(): int {
    return (int) $this->entity->created->value;
  }

  /**
   * Get entity seo.
   */
  protected function seo(): array {
    $meta = $this->entity->field_seo_and_social_media->entity;
    return [
      'meta_title' => $meta->field_meta_title->value,
      'meta_description' => $meta->field_meta_description->value,
    ];
  }

  /**
   * Get layouts.
   *
   * @todo available layouts need to be defined on the entity and handled in separate classes, to allow dynamic inclusion.
   */
  protected function layouts(): ?array {
    $groups = $this->entity->field_layouts;

    if ($groups == NULL) {
      return NULL;
    }

    $layouts = [];

    foreach ($groups as $group) {
      switch ($group->entity->getType()) {
        case 'text_content':
          if ($text = $group->entity->field_text->first()) {
            $layouts[] = ['text' => $text->value];
          }
      }
    }

    return $layouts;
  }

  /**
   * Get social media image.
   */
  protected function social(): ?array {
    $social = $this->entity->field_seo_and_social_media->entity;

    if ($social->field_social_media_image->isEmpty()) {
      return NULL;
    }

    $media = $social->field_social_media_image->entity;
    $file = $media->field_media_image->entity;
    $style = $this->entity->imageStyle->load('social_media_image');

    return [
      'src' => $style->buildUrl($file->getFileUri()),
    ];
  }

}
