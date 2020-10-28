<?php

declare(strict_types=1);

namespace App\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the team-member entity class.
 *
 * @ContentEntityType(
 *   id = "team_member",
 *   provider = "app",
 *   label = @Translation("Team Member"),
 *   label_collection = @Translation("Team Members"),
 *   handlers = {
 *     "storage" = "App\Service\Storage\TeamMemberStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "App\ListBuilder\TeamMemberListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "App\Form\TeamMember\EditForm",
 *       "edit" = "App\Form\TeamMember\EditForm",
 *       "delete" = "App\Form\TeamMember\DeleteForm"
 *     },
 *     "access" = "App\AccessControlHandler\TeamMemberAccessControlHandler",
 *   },
 *   base_table = "team_member",
 *   admin_permission = "administer team",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "fullname",
 *     "firstname" = "firstname",
 *     "lastname" = "lastname",
 *     "department" = "field_department",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/team-member/add",
 *     "edit-form" = "/admin/content/team-member/{team_member}/edit",
 *     "delete-form" = "/admin/content/team-member/{team_member}/delete",
 *     "collection" = "/admin/content/team-member"
 *   },
 *   field_ui_base_route = "entity.team_member.settings"
 * )
 */
class TeamMember extends ContentEntityBase implements TeamMemberInterface {

  use EntityChangedTrait;

  /**
   * Gets the team member creation timestamp.
   */
  public function getCreatedTime(): string {
    return $this->get('created')->value;
  }

  /**
   * Gets the calculated team member name.
   */
  public function getFullname(): string {
    return $this->get('fullname')->value;
  }

  /**
   * Gets the position held by the team member.
   */
  public function getPosition(): string {
    return $this->get('position')->value;
  }

  /**
   * Gets the department to which the team member belongs.
   */
  public function getDepartment(): string {
    return $this->get('field_department')->first()->entity->name->value;
  }

  /**
   * Sets the team member creation timestamp.
   */
  public function setCreatedTime(string $timestamp): TeamMemberInterface {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['fullname'] = BaseFieldDefinition::create('team_member_fullname')
      ->setName('fullname')
      ->setLabel(t('Full Name')->__toString())
      ->setComputed(TRUE)
      ->setClass('\App\Service\Field\ComputedFieldItemList')
      ->setDisplayConfigurable('view', FALSE)
      ->setDisplayConfigurable('form', FALSE);

    $fields['firstname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name')->__toString())
      ->setDescription(t('Provide the first name of the team member.')->__toString())
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['lastname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name')->__toString())
      ->setDescription(t('Provide the last name of the team member.')->__toString())
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['position'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Position')->__toString())
      ->setDescription(t('Provide the position held by the team member.')->__toString())
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email')->__toString())
      ->setDescription(t('Optionally provide the email address of the team member.')->__toString())
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
      ->setRequired(FALSE)
      ->addConstraint('UniqueField', [])
      ->setTranslatable(FALSE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['telephone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Telephone')->__toString())
      ->setDescription(t('Optionally provide a telephone number for the team member.')->__toString())
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
      ->setRequired(FALSE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the team member was created.')->__toString());

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed')->__toString())
      ->setDescription(t('The time that the team member was last edited.')->__toString());

    return $fields;
  }

}
