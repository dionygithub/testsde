<?php

namespace Drupal\admin_test\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Admin test entity entities.
 *
 * @ingroup admin_test
 */
interface AdminTestEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Admin test entity name.
   *
   * @return string
   *   Name of the Admin test entity.
   */
  public function getName();

  /**
   * Sets the Admin test entity name.
   *
   * @param string $name
   *   The Admin test entity name.
   *
   * @return \Drupal\admin_test\Entity\AdminTestEntityInterface
   *   The called Admin test entity entity.
   */
  public function setName($name);

  /**
   * Gets the Admin test entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Admin test entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Admin test entity creation timestamp.
   *
   * @param int $timestamp
   *   The Admin test entity creation timestamp.
   *
   * @return \Drupal\admin_test\Entity\AdminTestEntityInterface
   *   The called Admin test entity entity.
   */
  public function setCreatedTime($timestamp);

}
