<?php

namespace Drupal\admin_question\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Admin question entity entities.
 *
 * @ingroup admin_question
 */
interface AdminQuestionEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Admin question entity name.
   *
   * @return string
   *   Name of the Admin question entity.
   */
  public function getName();

  /**
   * Sets the Admin question entity name.
   *
   * @param string $name
   *   The Admin question entity name.
   *
   * @return \Drupal\admin_question\Entity\AdminQuestionEntityInterface
   *   The called Admin question entity entity.
   */
  public function setName($name);

  /**
   * Gets the Admin question entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Admin question entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Admin question entity creation timestamp.
   *
   * @param int $timestamp
   *   The Admin question entity creation timestamp.
   *
   * @return \Drupal\admin_question\Entity\AdminQuestionEntityInterface
   *   The called Admin question entity entity.
   */
  public function setCreatedTime($timestamp);

}
