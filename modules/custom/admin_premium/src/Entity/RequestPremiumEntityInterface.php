<?php

namespace Drupal\admin_premium\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Request premium entity entities.
 *
 * @ingroup admin_premium
 */
interface RequestPremiumEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Request premium entity name.
   *
   * @return string
   *   Name of the Request premium entity.
   */
  public function getName();

  /**
   * Sets the Request premium entity name.
   *
   * @param string $name
   *   The Request premium entity name.
   *
   * @return \Drupal\admin_premium\Entity\RequestPremiumEntityInterface
   *   The called Request premium entity entity.
   */
  public function setName($name);

  /**
   * Gets the Request premium entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Request premium entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Request premium entity creation timestamp.
   *
   * @param int $timestamp
   *   The Request premium entity creation timestamp.
   *
   * @return \Drupal\admin_premium\Entity\RequestPremiumEntityInterface
   *   The called Request premium entity entity.
   */
  public function setCreatedTime($timestamp);

}
