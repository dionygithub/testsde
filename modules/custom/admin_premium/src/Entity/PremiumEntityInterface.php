<?php

namespace Drupal\admin_premium\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Premium entity entities.
 *
 * @ingroup admin_premium
 */
interface PremiumEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Premium entity name.
   *
   * @return string
   *   Name of the Premium entity.
   */
  public function getName();

  /**
   * Sets the Premium entity name.
   *
   * @param string $name
   *   The Premium entity name.
   *
   * @return \Drupal\admin_premium\Entity\PremiumEntityInterface
   *   The called Premium entity entity.
   */
  public function setName($name);

  /**
   * Gets the Premium entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Premium entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Premium entity creation timestamp.
   *
   * @param int $timestamp
   *   The Premium entity creation timestamp.
   *
   * @return \Drupal\admin_premium\Entity\PremiumEntityInterface
   *   The called Premium entity entity.
   */
  public function setCreatedTime($timestamp);

}
