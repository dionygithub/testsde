<?php

namespace Drupal\admin_premium\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Premium entity entity.
 *
 * @ingroup admin_premium
 *
 * @ContentEntityType(
 *   id = "premium_entity",
 *   render_cache = FALSE,
 *   label = @Translation("Premium entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\admin_premium\PremiumEntityListBuilder",
 *     "views_data" = "Drupal\admin_premium\Entity\PremiumEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\admin_premium\Form\PremiumEntityForm",
 *       "add" = "Drupal\admin_premium\Form\PremiumEntityForm",
 *       "edit" = "Drupal\admin_premium\Form\PremiumEntityForm",
 *       "delete" = "Drupal\admin_premium\Form\PremiumEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\admin_premium\PremiumEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\admin_premium\PremiumEntityAccessControlHandler",
 *   },
 *   base_table = "premium_entity",
 *   translatable = FALSE,
 *   admin_permission = "administer premium entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/premium/{premium_entity}",
 *     "add-form" = "/admin/structure/premium_entity/add",
 *     "edit-form" = "/admin/structure/premium_entity/{premium_entity}/edit",
 *     "delete-form" = "/admin/structure/premium_entity/{premium_entity}/delete",
 *     "collection" = "/admin/structure/premium_entity",
 *   },
 *   field_ui_base_route = "premium_entity.settings"
 * )
 */
class PremiumEntity extends ContentEntityBase implements PremiumEntityInterface {

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
      ->setDescription(t('The user ID of author of the Premium entity entity.'))
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
      ->setDescription(t('The name of the Premium entity entity.'))
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

    $fields['status']->setDescription(t('A boolean indicating whether the Premium entity is published.'))
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


    $fields['points'] = BaseFieldDefinition::create('integer')
        ->setLabel('Points')
        ->setDescription('Points')
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
