<?php

namespace Drupal\admin_test\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Admin test entity entity.
 *
 * @ingroup admin_test
 *
 * @ContentEntityType(
 *   id = "admin_test_entity",
 *   label = @Translation("Admin test entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\admin_test\AdminTestEntityListBuilder",
 *     "views_data" = "Drupal\admin_test\Entity\AdminTestEntityViewsData",
 *     "translation" = "Drupal\admin_test\AdminTestEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\admin_test\Form\AdminTestEntityForm",
 *       "add" = "Drupal\admin_test\Form\AdminTestEntityForm",
 *       "edit" = "Drupal\admin_test\Form\AdminTestEntityForm",
 *       "delete" = "Drupal\admin_test\Form\AdminTestEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\admin_test\AdminTestEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\admin_test\AdminTestEntityAccessControlHandler",
 *   },
 *   base_table = "admin_test_entity",
 *   data_table = "admin_test_entity_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer admin test entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/test/{admin_test_entity}",
 *     "add-form" = "/admin/structure/admin_test_entity/add",
 *     "edit-form" = "/admin/structure/admin_test_entity/{admin_test_entity}/edit",
 *     "delete-form" = "/admin/structure/admin_test_entity/{admin_test_entity}/delete",
 *     "collection" = "/admin/structure/admin_test_entity",
 *   },
 *   field_ui_base_route = "admin_test_entity.settings"
 * )
 */
class AdminTestEntity extends ContentEntityBase implements AdminTestEntityInterface {

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
      ->setDescription(t('The user ID of author of the Admin test entity entity.'))
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
      ->setDescription(t('The name of the Admin test entity entity.'))
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

    $fields['status']->setDescription(t('A boolean indicating whether the Admin test entity is published.'))
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

    $fields['entity_reference_tax'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel('Entity Reference Taxonomy')
        ->setDescription('Entity Reference Taxonomy')
        ->setSetting('target_type', 'taxonomy_term')
        ->setSetting('handler', 'default:taxonomy_term')
        ->setSetting('handler_settings',
            array(
                'target_bundles' => array(
                    'vocabulary_name' => 'categorias'
                )))
        ->setDisplayOptions('view', array(
            'label' => 'hidden',
            'type' => 'author',
            'weight' => 0,
        ))
        ->setDisplayOptions('form', array(
            'type' => 'entity_reference_autocomplete',
            'weight' => 3,
            'settings' => array(
                'match_operator' => 'CONTAINS',
                'size' => '10',
                'autocomplete_type' => 'categorias',
                'placeholder' => '',
            ),
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);


    $fields['path'] = BaseFieldDefinition::create('path')
        ->setLabel(t('URL alias'))
        ->setTranslatable(TRUE)
        ->setDisplayOptions('form', [
            'type' => 'path',
            'weight' => 30,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setComputed(TRUE);

    $fields['question_reference_entity'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel('Question Reference Entity')
        ->setDescription('Question Reference Entity')
        ->setSetting('target_type', 'admin_question_entity')
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
        ->setDisplayConfigurable('view', TRUE)
        ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED);

    //Integer
    $fields['likes'] = BaseFieldDefinition::create('integer')
        ->setLabel('Likes')
        ->setDescription('Likes')
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

    //Integer
    $fields['nolikes'] = BaseFieldDefinition::create('integer')
        ->setLabel('nolikes')
        ->setDescription('nolikes')
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

    //Integer
      $fields['time'] = BaseFieldDefinition::create('integer')
        ->setLabel('time')
        ->setDescription('time (min)')
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

      $fields['destacado'] = BaseFieldDefinition::create('boolean')
          ->setLabel('Destacado')
          ->setDescription('Destacado')
          ->setDisplayOptions('form', array(
              'type' => 'boolean_checkbox',
              'settings' => array(
                  'display_label' => TRUE,
              ),
              'weight' => 0,
          ))

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


      $fields['tipo'] = BaseFieldDefinition::create('entity_reference')
          ->setLabel('Tipo')
          ->setDescription('Tipo Test')
          ->setSetting('target_type', 'taxonomy_term')
          ->setSetting('handler', 'default:taxonomy_term')
          ->setSetting('handler_settings',
              array(
                  'target_bundles' => array(
                      'vocabulary_name' => 'tipo_tests'
                  )))
          ->setDisplayOptions('view', array(
              'label' => 'hidden',
              'type' => 'author',
              'weight' => 0,
          ))
          ->setDisplayOptions('form', array(
              'type' => 'entity_reference_autocomplete',
              'weight' => 3,
              'settings' => array(
                  'match_operator' => 'CONTAINS',
                  'size' => '10',
                  'autocomplete_type' => 'tipo_tests',
                  'placeholder' => '',
              ),
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);


      $fields['resultados'] = BaseFieldDefinition::create('entity_reference')
          ->setLabel('Resultados')
          ->setDescription('Resultados Test')
          ->setSetting('target_type', 'taxonomy_term')
          ->setSetting('handler', 'default:taxonomy_term')
          ->setSetting('handler_settings',
              array(
                  'target_bundles' => array(
                      'vocabulary_name' => 'resultados'
                  )))
          ->setDisplayOptions('view', array(
              'label' => 'hidden',
              'type' => 'author',
              'weight' => 0,
          ))
          ->setDisplayOptions('form', array(
              'type' => 'entity_reference_autocomplete',
              'weight' => 3,
              'settings' => array(
                  'match_operator' => 'CONTAINS',
                  'size' => '10',
                  'autocomplete_type' => 'resultados',
                  'placeholder' => '',
              ),
          ))
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE)
          ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED);

    return $fields;
  }

}
