<?php

namespace Drupal\admin_test\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Admin test entity entities.
 */
class AdminTestEntityViewsData extends EntityViewsData {

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
