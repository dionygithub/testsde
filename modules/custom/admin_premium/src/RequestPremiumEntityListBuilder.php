<?php

namespace Drupal\admin_premium;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Request premium entity entities.
 *
 * @ingroup admin_premium
 */
class RequestPremiumEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Request premium entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\admin_premium\Entity\RequestPremiumEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.request_premium_entity.edit_form',
      ['request_premium_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
