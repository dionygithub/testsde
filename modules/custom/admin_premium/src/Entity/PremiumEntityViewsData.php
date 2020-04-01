<?php

namespace Drupal\admin_premium\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Premium entity entities.
 */
class PremiumEntityViewsData extends EntityViewsData {

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
