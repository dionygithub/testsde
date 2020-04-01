<?php

namespace Drupal\admin_answer\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Admin answer entity entities.
 *
 * @ingroup admin_answer
 */
interface AdminAnswerEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Admin answer entity name.
   *
   * @return string
   *   Name of the Admin answer entity.
   */
  public function getName();

  /**
   * Sets the Admin answer entity name.
   *
   * @param string $name
   *   The Admin answer entity name.
   *
   * @return \Drupal\admin_answer\Entity\AdminAnswerEntityInterface
   *   The called Admin answer entity entity.
   */
  public function setName($name);

  /**
   * Gets the Admin answer entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Admin answer entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Admin answer entity creation timestamp.
   *
   * @param int $timestamp
   *   The Admin answer entity creation timestamp.
   *
   * @return \Drupal\admin_answer\Entity\AdminAnswerEntityInterface
   *   The called Admin answer entity entity.
   */
  public function setCreatedTime($timestamp);

}
