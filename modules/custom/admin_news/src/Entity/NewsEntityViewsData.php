<?php

namespace Drupal\admin_news\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for News entity entities.
 */
class NewsEntityViewsData extends EntityViewsData {

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
