<?php

declare(strict_types=1);

namespace App\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the timeline-event entity class.
 *
 * @ContentEntityType(
 *   id = "timeline_event",
 *   provider = "app",
 *   label = @Translation("Timeline Event"),
 *   label_collection = @Translation("Timeline Events"),
 *   handlers = {
 *     "storage" = "App\Storage\TimelineEventStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "App\ListBuilder\TimelineEventListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "App\Form\TimelineEvent\EditForm",
 *       "edit" = "App\Form\TimelineEvent\EditForm",
 *       "delete" = "App\Form\TimelineEvent\DeleteForm"
 *     },
 *     "access" = "App\AccessControlHandler\TimelineEventAccessControlHandler",
 *   },
 *   base_table = "timeline_event",
 *   admin_permission = "administer timeline",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "heading",
 *     "heading" = "heading",
 *     "date" = "date",
 *     "details" = "details",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/timeline-event/add",
 *     "edit-form" = "/admin/content/timeline-event/{team_member}/edit",
 *     "delete-form" = "/admin/content/timeline-event/{team_member}/delete",
 *     "collection" = "/admin/content/timeline-event"
 *   },
 *   field_ui_base_route = "entity.timeline_event.settings"
 * )
 */
class TimelineEvent extends ContentEntityBase implements TimelineEventInterface {

  use EntityChangedTrait;

  /**
   * Gets the timeline event creation timestamp.
   */
  public function getCreatedTime(): string {
    return $this->get('created')->value;
  }

  /**
   * Gets the heading of the timeline event.
   */
  public function getHeading(): string {
    return $this->get('heading')->value;
  }

  /**
   * Gets the timestamp of the timeline event.
   */
  public function getTimestamp(): int {
    return strtotime($this->get('date')->value);
  }

  /**
   * Gets the date of the timeline event.
   */
  public function getDate(?string $date_format = 'F Y'): string {
    return date($date_format, $this->getTimestamp());
  }

  /**
   * Gets the details of the timeline event.
   */
  public function getDetails(): string {
    return $this->get('details')->value;
  }

  /**
   * Sets the team member creation timestamp.
   */
  public function setCreatedTime(string $timestamp): TimelineEventInterface {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['heading'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Heading')->__toString())
      ->setDescription(t('Provide a heading for the timeline event.')->__toString())
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setRequired(TRUE)
      ->addConstraint('UniqueField')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Date')->__toString())
      ->setDescription(t('Provide the date of the timeline event.')->__toString())
      ->setSetting('datetime_type', 'date')
      ->setSettings([
        'datetime_type' => 'date',
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setRequired(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['details'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Details')->__toString())
      ->setDescription(t('Outline the details of the timeline event.')->__toString())
      ->setSettings([
        'default_value' => '',
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'weight' => -5,
      ])
      ->setRequired(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on')->__toString())
      ->setDescription(t('The time that the timeline event was created.')->__toString());

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed')->__toString())
      ->setDescription(t('The time that the timeline event was last edited.')->__toString());

    return $fields;
  }

}
