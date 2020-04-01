<?php

namespace Drupal\admin_premium\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining User premium entity entities.
 *
 * @ingroup admin_premium
 */
interface UserPremiumEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the User premium entity name.
   *
   * @return string
   *   Name of the User premium entity.
   */
  public function getName();

  /**
   * Sets the User premium entity name.
   *
   * @param string $name
   *   The User premium entity name.
   *
   * @return \Drupal\admin_premium\Entity\UserPremiumEntityInterface
   *   The called User premium entity entity.
   */
  public function setName($name);

  /**
   * Gets the User premium entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the User premium entity.
   */
  public function getCreatedTime();

  /**
   * Sets the User premium entity creation timestamp.
   *
   * @param int $timestamp
   *   The User premium entity creation timestamp.
   *
   * @return \Drupal\admin_premium\Entity\UserPremiumEntityInterface
   *   The called User premium entity entity.
   */
  public function setCreatedTime($timestamp);

}
