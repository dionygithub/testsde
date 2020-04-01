<?php

namespace Drupal\admin_points_extras;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Points extras entity entities.
 *
 * @ingroup admin_points_extras
 */
class PointsExtrasEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Points extras entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\admin_points_extras\Entity\PointsExtrasEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.points_extras_entity.edit_form',
      ['points_extras_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
