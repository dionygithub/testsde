<?php

namespace Drupal\admin_answer\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Admin answer entity entity.
 *
 * @ingroup admin_answer
 *
 * @ContentEntityType(
 *   id = "admin_answer_entity",
 *   label = @Translation("Admin answer entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\admin_answer\AdminAnswerEntityListBuilder",
 *     "views_data" = "Drupal\admin_answer\Entity\AdminAnswerEntityViewsData",
 *     "translation" = "Drupal\admin_answer\AdminAnswerEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\admin_answer\Form\AdminAnswerEntityForm",
 *       "add" = "Drupal\admin_answer\Form\AdminAnswerEntityForm",
 *       "edit" = "Drupal\admin_answer\Form\AdminAnswerEntityForm",
 *       "delete" = "Drupal\admin_answer\Form\AdminAnswerEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\admin_answer\AdminAnswerEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\admin_answer\AdminAnswerEntityAccessControlHandler",
 *   },
 *   base_table = "admin_answer_entity",
 *   data_table = "admin_answer_entity_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer admin answer entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/admin_answer_entity/{admin_answer_entity}",
 *     "add-form" = "/admin/structure/admin_answer_entity/add",
 *     "edit-form" = "/admin/structure/admin_answer_entity/{admin_answer_entity}/edit",
 *     "delete-form" = "/admin/structure/admin_answer_entity/{admin_answer_entity}/delete",
 *     "collection" = "/admin/structure/admin_answer_entity",
 *   },
 *   field_ui_base_route = "admin_answer_entity.settings"
 * )
 */
class AdminAnswerEntity extends ContentEntityBase implements AdminAnswerEntityInterface {

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
      ->setDescription(t('The user ID of author of the Admin answer entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
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
      ->setDescription(t('The name of the Admin answer entity entity.'))
      ->setSettings([
        'max_length' => 500,
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

    $fields['status']->setDescription(t('A boolean indicating whether the Admin answer entity is published.'))
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

    $fields['respuesta_valida'] = BaseFieldDefinition::create('boolean')
        ->setLabel('Valida')
        ->setDescription('Respuesta Valida')
        ->setDisplayOptions('form', array(
            'type' => 'boolean_checkbox',
            'settings' => array(
                'display_label' => TRUE,
            ),
            'weight' => 0,
        ))
        ->setDisplayConfigurable('form', TRUE);


    // Campo de imagen
    $fields['imagen'] = BaseFieldDefinition::create('image')
        ->setLabel('Imagen')
        ->setDescription('Imagen')
        ->setSettings(
            [
                'file_directory' => 'test',
                'alt_field_required' => FALSE,
                'file_extensions' => 'png jpg jpeg',
            ]
        )
        ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'image',
            'weight' => 0,
        ])
        ->setDisplayOptions('form', [
            'type' => 'image_image',
            'weight' => 5,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);


    $fields['description'] = BaseFieldDefinition::create('text_long')
        ->setLabel('Description')
        ->setDescription('Description')
        ->setTranslatable(TRUE)
        ->setDisplayOptions('view', array(
            'label' => 'hidden',
            'type' => 'text_default',
            'weight' => 4,
        ))
        ->setDisplayConfigurable('view', TRUE)
        ->setDisplayOptions('form', array(
            'type' => 'text_textfield',
            'weight' => 4,
        ))
        ->setDisplayConfigurable('form', TRUE);


    $fields['puntuacion'] = BaseFieldDefinition::create('integer')
        ->setLabel('Puntuacion')
        ->setDescription('Puntuacion')
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


    return $fields;
  }

}
