<?php

namespace Drupal\admin_points_extras\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Points extras entity entities.
 */
class PointsExtrasEntityViewsData extends EntityViewsData {

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
