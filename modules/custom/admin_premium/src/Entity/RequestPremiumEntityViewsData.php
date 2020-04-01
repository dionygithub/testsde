<?php

namespace Drupal\admin_premium\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Request premium entity entities.
 */
class RequestPremiumEntityViewsData extends EntityViewsData {

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
