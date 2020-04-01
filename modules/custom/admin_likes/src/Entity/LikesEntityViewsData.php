<?php

namespace Drupal\admin_likes\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Likes entity entities.
 */
class LikesEntityViewsData extends EntityViewsData {

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
