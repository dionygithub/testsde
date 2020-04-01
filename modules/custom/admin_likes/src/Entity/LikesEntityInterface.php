<?php

namespace Drupal\admin_likes\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Likes entity entities.
 *
 * @ingroup admin_likes
 */
interface LikesEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Likes entity name.
   *
   * @return string
   *   Name of the Likes entity.
   */
  public function getName();

  /**
   * Sets the Likes entity name.
   *
   * @param string $name
   *   The Likes entity name.
   *
   * @return \Drupal\admin_likes\Entity\LikesEntityInterface
   *   The called Likes entity entity.
   */
  public function setName($name);

  /**
   * Gets the Likes entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Likes entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Likes entity creation timestamp.
   *
   * @param int $timestamp
   *   The Likes entity creation timestamp.
   *
   * @return \Drupal\admin_likes\Entity\LikesEntityInterface
   *   The called Likes entity entity.
   */
  public function setCreatedTime($timestamp);

}
