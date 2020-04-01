<?php

namespace Drupal\admin_record\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Admin record entity entities.
 *
 * @ingroup admin_record
 */
interface AdminRecordEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Admin record entity name.
   *
   * @return string
   *   Name of the Admin record entity.
   */
  public function getName();

  /**
   * Sets the Admin record entity name.
   *
   * @param string $name
   *   The Admin record entity name.
   *
   * @return \Drupal\admin_record\Entity\AdminRecordEntityInterface
   *   The called Admin record entity entity.
   */
  public function setName($name);

  /**
   * Gets the Admin record entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Admin record entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Admin record entity creation timestamp.
   *
   * @param int $timestamp
   *   The Admin record entity creation timestamp.
   *
   * @return \Drupal\admin_record\Entity\AdminRecordEntityInterface
   *   The called Admin record entity entity.
   */
  public function setCreatedTime($timestamp);

}
