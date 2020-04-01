<?php

namespace Drupal\actions_points\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Actions entity entities.
 */
class ActionsEntityViewsData extends EntityViewsData {

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
