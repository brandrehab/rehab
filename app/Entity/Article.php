<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Entity\Node;
use App\Service\Entity\AssignsTitleFromSeoFieldTrait;
use App\Service\Entity\UpdatesChildMenuLinksTrait;
use App\Service\Entity\ControlsMenuLinkStatusTrait;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Entity for content of type article.
 */
class Article extends Node implements ArticleInterface {

  use AssignsTitleFromSeoFieldTrait;
  use UpdatesChildMenuLinksTrait;
  use ControlsMenuLinkStatusTrait;

  /**
   * Get the optional entity layouts.
   */
  public function getLayouts(): ?array {
    $groups = $this->field_layouts;

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
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    $this->assignTitleFromSeoField();
    $this->updateChildMenuLinks($storage);
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE): void {
    parent::postSave($storage, $update);
    $this->controlMenuItemStatus();
  }

}
