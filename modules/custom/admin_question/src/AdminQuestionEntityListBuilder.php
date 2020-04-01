<?php

namespace Drupal\admin_question;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Admin question entity entities.
 *
 * @ingroup admin_question
 */
class AdminQuestionEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Admin question entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\admin_question\Entity\AdminQuestionEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.admin_question_entity.edit_form',
      ['admin_question_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
