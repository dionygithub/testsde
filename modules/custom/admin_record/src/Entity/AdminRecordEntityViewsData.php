<?php

namespace Drupal\admin_record\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Admin record entity entities.
 */
class AdminRecordEntityViewsData extends EntityViewsData {

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
