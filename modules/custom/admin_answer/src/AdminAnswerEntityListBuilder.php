<?php

namespace Drupal\admin_answer;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Admin answer entity entities.
 *
 * @ingroup admin_answer
 */
class AdminAnswerEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Admin answer entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\admin_answer\Entity\AdminAnswerEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.admin_answer_entity.edit_form',
      ['admin_answer_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
