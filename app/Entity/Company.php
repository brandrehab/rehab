<?php

declare(strict_types=1);

namespace App\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the company entity class.
 *
 * @ContentEntityType(
 *   id = "company",
 *   provider = "app",
 *   label = @Translation("Company"),
 *   label_collection = @Translation("Companies"),
 *   handlers = {
 *     "storage" = "App\Storage\CompanyStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "App\ListBuilder\CompanyListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "App\Form\Company\EditForm",
 *       "edit" = "App\Form\Company\EditForm",
 *       "delete" = "App\Form\Company\DeleteForm"
 *     },
 *     "access" = "App\AccessControlHandler\CompanyAccessControlHandler",
 *   },
 *   base_table = "company",
 *   admin_permission = "administer company",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "name" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/company/add",
 *     "edit-form" = "/admin/content/company/{company}/edit",
 *     "delete-form" = "/admin/content/company/{company}/delete",
 *     "collection" = "/admin/content/company"
 *   },
 *   field_ui_base_route = "entity.company.settings"
 * )
 */
class Company extends ContentEntityBase implements CompanyInterface {

  use EntityChangedTrait;

  /**
   * Get the company name.
   */
  public function getName(): string {
    return $this->get('name')->value;
  }

  /**
   * Get the optional registered company name.
   */
  public function getRegisteredName(): ?string {
    return $this->get('field_registered_name')->value;
  }

  /**
   * Get the optional company registration number.
   */
  public function getRegistrationNumber(): ?string {
    return $this->get('field_registration_number')->value;
  }

  /**
   * Get the optional country of registration.
   */
  public function getCountryOfRegistration(): ?string {
    return $this->get('field_country_of_registration')->value;
  }

  /**
   * Gets the company creation timestamp.
   */
  public function getCreatedTime(): string {
    return $this->get('created')->value;
  }

  /**
   * Set the company name.
   */
  public function setName(string $name): CompanyInterface {
    $this->set('name', $name);
    return $this;
  }

  /**
   * Set the registered company name.
   */
  public function setRegisteredName(string $registered_name): CompanyInterface {
    $this->set('field_registered_name', $registered_name);
    return $this;
  }

  /**
   * Set the company registration number.
   */
  public function setRegistrationNumber(string $number): CompanyInterface {
    $this->set('field_registration_number', $number);
    return $this;
  }

  /**
   * Set the country of registration.
   */
  public function setCountryOfRegistration(string $country): CompanyInterface {
    $this->set('field_country_of_registration', $country);
    return $this;
  }

  /**
   * Sets the company creation timestamp.
   */
  public function setCreatedTime(string $timestamp): CompanyInterface {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * Checks whether all the registration information exists.
   */
  public function isRegistered(): bool {
    if (
      $this->getRegisteredName() &&
      $this->getRegistrationNumber() &&
      $this->getCountryOfRegistration()
    ) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name')->__toString())
      ->setDescription(t('Provide the name of the company.')->__toString())
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

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on')->__toString())
      ->setDescription(t('The time that the company was created.')->__toString());

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed')->__toString())
      ->setDescription(t('The time that the company was last edited.')->__toString());

    return $fields;
  }

}
