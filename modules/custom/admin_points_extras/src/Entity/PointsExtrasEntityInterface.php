<?php

namespace Drupal\admin_points_extras\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Points extras entity entities.
 *
 * @ingroup admin_points_extras
 */
interface PointsExtrasEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Points extras entity name.
   *
   * @return string
   *   Name of the Points extras entity.
   */
  public function getName();

  /**
   * Sets the Points extras entity name.
   *
   * @param string $name
   *   The Points extras entity name.
   *
   * @return \Drupal\admin_points_extras\Entity\PointsExtrasEntityInterface
   *   The called Points extras entity entity.
   */
  public function setName($name);

  /**
   * Gets the Points extras entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Points extras entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Points extras entity creation timestamp.
   *
   * @param int $timestamp
   *   The Points extras entity creation timestamp.
   *
   * @return \Drupal\admin_points_extras\Entity\PointsExtrasEntityInterface
   *   The called Points extras entity entity.
   */
  public function setCreatedTime($timestamp);

}
