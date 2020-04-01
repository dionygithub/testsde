<?php

namespace Drupal\admin_news\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the News entity entity.
 *
 * @ingroup admin_news
 *
 * @ContentEntityType(
 *   id = "news_entity",
 *   label = @Translation("News entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\admin_news\NewsEntityListBuilder",
 *     "views_data" = "Drupal\admin_news\Entity\NewsEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\admin_news\Form\NewsEntityForm",
 *       "add" = "Drupal\admin_news\Form\NewsEntityForm",
 *       "edit" = "Drupal\admin_news\Form\NewsEntityForm",
 *       "delete" = "Drupal\admin_news\Form\NewsEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\admin_news\NewsEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\admin_news\NewsEntityAccessControlHandler",
 *   },
 *   base_table = "news_entity",
 *   translatable = FALSE,
 *   admin_permission = "administer news entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/news_entity/{news_entity}",
 *     "add-form" = "/admin/structure/news_entity/add",
 *     "edit-form" = "/admin/structure/news_entity/{news_entity}/edit",
 *     "delete-form" = "/admin/structure/news_entity/{news_entity}/delete",
 *     "collection" = "/admin/structure/news_entity",
 *   },
 *   field_ui_base_route = "news_entity.settings"
 * )
 */
class NewsEntity extends ContentEntityBase implements NewsEntityInterface {

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
      ->setDescription(t('The user ID of author of the News entity entity.'))
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
      ->setDescription(t('The name of the News entity entity.'))
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

    $fields['status']->setDescription(t('A boolean indicating whether the News entity is published.'))
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

    $fields['description'] = BaseFieldDefinition::create('string_long')
        ->setLabel('String description')
        ->setDescription('String description')
        ->setTranslatable(TRUE)
        ->setSettings(array(
            'default_value' => '',
        ))
        ->setDisplayOptions('view', array(
            'label' => 'above',
            'type' => 'string',
            'weight' => 4,
        ))
        ->setDisplayOptions('form', array(
            'type' => 'text_textfield',
            'weight' => 4,
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);


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

    return $fields;
  }

}
