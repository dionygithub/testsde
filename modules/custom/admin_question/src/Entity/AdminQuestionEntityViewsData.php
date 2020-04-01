<?php

namespace Drupal\admin_question\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Admin question entity entities.
 */
class AdminQuestionEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
