<?php

namespace Drupal\actions_points\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Actions entity entities.
 *
 * @ingroup actions_points
 */
interface ActionsEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Actions entity name.
   *
   * @return string
   *   Name of the Actions entity.
   */
  public function getName();

  /**
   * Sets the Actions entity name.
   *
   * @param string $name
   *   The Actions entity name.
   *
   * @return \Drupal\actions_points\Entity\ActionsEntityInterface
   *   The called Actions entity entity.
   */
  public function setName($name);

  /**
   * Gets the Actions entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Actions entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Actions entity creation timestamp.
   *
   * @param int $timestamp
   *   The Actions entity creation timestamp.
   *
   * @return \Drupal\actions_points\Entity\ActionsEntityInterface
   *   The called Actions entity entity.
   */
  public function setCreatedTime($timestamp);

}
