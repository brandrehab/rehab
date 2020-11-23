<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Entity\Node;
use App\Traits\Entity\AssignsTitleFromSeoFieldTrait;
use App\Traits\Entity\UpdatesChildMenuLinksTrait;
use App\Traits\Entity\ControlsMenuLinkStatusTrait;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Entity for content of type service.
 */
class Service extends Node implements ServiceInterface {

  use AssignsTitleFromSeoFieldTrait;
  use UpdatesChildMenuLinksTrait;
  use ControlsMenuLinkStatusTrait;

  /**
   * Get the optional entity layouts.
   */
  public function getLayouts(): ?array {
    $groups = $this->get('field_layouts');

    if ($groups == NULL) {
      return NULL;
    }

    $layouts = [];

    foreach ($groups as $group) {
      switch ($group->entity->getType()) {
        case 'text_content':
          if ($text = $group->entity->get('field_text')->first()) {
            $layouts[] = ['text' => $text->value];
          }
          break;
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
