<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Entity\Node;
use App\Traits\Entity\AssignsTitleFromSeoFieldTrait;
use App\Traits\Entity\ControlsMenuLinkStatusTrait;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Entity for content of type error.
 */
class Error extends Node implements ErrorInterface {

  use AssignsTitleFromSeoFieldTrait;
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
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE): void {
    parent::postSave($storage, $update);
    $this->controlMenuItemStatus();
  }

}
