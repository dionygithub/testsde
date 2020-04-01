<?php

namespace Drupal\admin_answer\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Admin answer entity entities.
 */
class AdminAnswerEntityViewsData extends EntityViewsData {

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
