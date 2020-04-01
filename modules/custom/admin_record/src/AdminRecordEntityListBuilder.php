<?php

namespace Drupal\admin_record;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Admin record entity entities.
 *
 * @ingroup admin_record
 */
class AdminRecordEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Admin record entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\admin_record\Entity\AdminRecordEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.admin_record_entity.edit_form',
      ['admin_record_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
