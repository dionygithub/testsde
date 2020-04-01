<?php

namespace Drupal\admin_record\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Admin record entity entity.
 *
 * @ingroup admin_record
 *
 * @ContentEntityType(
 *   id = "admin_record_entity",
 *   label = @Translation("Admin record entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\admin_record\AdminRecordEntityListBuilder",
 *     "views_data" = "Drupal\admin_record\Entity\AdminRecordEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\admin_record\Form\AdminRecordEntityForm",
 *       "add" = "Drupal\admin_record\Form\AdminRecordEntityForm",
 *       "edit" = "Drupal\admin_record\Form\AdminRecordEntityForm",
 *       "delete" = "Drupal\admin_record\Form\AdminRecordEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\admin_record\AdminRecordEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\admin_record\AdminRecordEntityAccessControlHandler",
 *   },
 *   base_table = "admin_record_entity",
 *   translatable = FALSE,
 *   admin_permission = "administer admin record entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/admin_record_entity/{admin_record_entity}",
 *     "add-form" = "/admin/structure/admin_record_entity/add",
 *     "edit-form" = "/admin/structure/admin_record_entity/{admin_record_entity}/edit",
 *     "delete-form" = "/admin/structure/admin_record_entity/{admin_record_entity}/delete",
 *     "collection" = "/admin/structure/admin_record_entity",
 *   },
 *   field_ui_base_route = "admin_record_entity.settings"
 * )
 */
class AdminRecordEntity extends ContentEntityBase implements AdminRecordEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Admin record entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Admin record entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Admin record entity is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['test_reference_entity'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel('Test Reference Entity')
        ->setDescription('Test Reference Entity')
        ->setSetting('target_type', 'admin_test_entity')
        ->setSetting('handler', 'default')
        ->setTranslatable(TRUE)
        ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'author',
            'weight' => 0,
        ])
        ->setDisplayOptions('form', [
            'type' => 'entity_reference_autocomplete',
            'weight' => 0,
            'settings' => [
                'match_operator' => 'CONTAINS',
                'size' => '60',
                'autocomplete_type' => 'tags',
                'placeholder' => '',
            ],
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['totalattempts'] = BaseFieldDefinition::create('integer')
        ->setLabel('Attempts')
        ->setDescription('Attempts')
        ->setDisplayOptions('view', array(
            'label' => 'above',
            'type' => 'integer',
            'weight' => 0,
        ))
        ->setDisplayOptions('form', array(
            'type' => 'number',
            'weight' => 0,
        ))
        ->setSetting('size', 'big')
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['totalquestions'] = BaseFieldDefinition::create('integer')
        ->setLabel('Questions')
        ->setDescription('Questions')
        ->setDisplayOptions('view', array(
            'label' => 'above',
            'type' => 'integer',
            'weight' => 0,
        ))
        ->setDisplayOptions('form', array(
            'type' => 'number',
            'weight' => 0,
        ))
        ->setSetting('size', 'big')
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    $fields['totalcorrectquestions'] = BaseFieldDefinition::create('integer')
        ->setLabel('Correct Questions')
        ->setDescription('Correct Questions')
        ->setDisplayOptions('view', array(
            'label' => 'above',
            'type' => 'integer',
            'weight' => 0,
        ))
        ->setDisplayOptions('form', array(
            'type' => 'number',
            'weight' => 0,
        ))
        ->setSetting('size', 'big')
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    //Decimal
    $fields['points'] = BaseFieldDefinition::create('decimal')
        ->setLabel('Points')
        ->setDescription('Points')
        ->setSettings(array(
            'precision' => 5,
            'scale' => 2,
            'decimal_separator' => '.',
        ))
        ->setDisplayOptions('view', array(
            'label' => 'above',
            'type' => 'decimal',
            'weight' => -3,
        ))
        ->setDisplayOptions('form', array(
            'type' => 'number',
            'weight' => -3,
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
