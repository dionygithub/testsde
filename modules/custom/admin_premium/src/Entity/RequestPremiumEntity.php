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
 * Defines the Request premium entity entity.
 *
 * @ingroup admin_premium
 *
 * @ContentEntityType(
 *   id = "request_premium_entity",
 *   label = @Translation("Request premium entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\admin_premium\RequestPremiumEntityListBuilder",
 *     "views_data" = "Drupal\admin_premium\Entity\RequestPremiumEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\admin_premium\Form\RequestPremiumEntityForm",
 *       "add" = "Drupal\admin_premium\Form\RequestPremiumEntityForm",
 *       "edit" = "Drupal\admin_premium\Form\RequestPremiumEntityForm",
 *       "delete" = "Drupal\admin_premium\Form\RequestPremiumEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\admin_premium\RequestPremiumEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\admin_premium\RequestPremiumEntityAccessControlHandler",
 *   },
 *   base_table = "request_premium_entity",
 *   translatable = FALSE,
 *   admin_permission = "administer request premium entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/request_premium_entity/{request_premium_entity}",
 *     "add-form" = "/admin/structure/request_premium_entity/add",
 *     "edit-form" = "/admin/structure/request_premium_entity/{request_premium_entity}/edit",
 *     "delete-form" = "/admin/structure/request_premium_entity/{request_premium_entity}/delete",
 *     "collection" = "/admin/structure/request_premium_entity",
 *   },
 *   field_ui_base_route = "request_premium_entity.settings"
 * )
 */
class RequestPremiumEntity extends ContentEntityBase implements RequestPremiumEntityInterface {

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
      ->setDescription(t('The user ID of author of the Request premium entity entity.'))
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
      ->setDescription(t('The name of the Request premium entity entity.'))
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

    $fields['status']->setDescription(t('A boolean indicating whether the Request premium entity is published.'))
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

    $fields['premium_reference_entity'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel('Question Reference Entity')
        ->setDescription('Premium Reference Entity')
        ->setSetting('target_type', 'premium_entity')
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

    //Decimal
    $fields['premium_price'] = BaseFieldDefinition::create('decimal')
        ->setLabel('Premium Price')
        ->setDescription('Premium Price')
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


    $fields['status_tax'] = BaseFieldDefinition::create('entity_reference')
        ->setLabel('Status Reference Taxonomy')
        ->setDescription('Status Reference Taxonomy')
        ->setSetting('target_type', 'taxonomy_term')
        ->setSetting('handler', 'default:taxonomy_term')
        ->setSetting('handler_settings',
            array(
                'target_bundles' => array(
                    'vocabulary_name' => 'estados_solicitudes'
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
                'autocomplete_type' => 'estados_solicitudes',
                'placeholder' => '',
            ),
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);


    return $fields;
  }

}
