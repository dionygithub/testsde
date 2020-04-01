<?php

namespace Drupal\admin_news\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining News entity entities.
 *
 * @ingroup admin_news
 */
interface NewsEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the News entity name.
   *
   * @return string
   *   Name of the News entity.
   */
  public function getName();

  /**
   * Sets the News entity name.
   *
   * @param string $name
   *   The News entity name.
   *
   * @return \Drupal\admin_news\Entity\NewsEntityInterface
   *   The called News entity entity.
   */
  public function setName($name);

  /**
   * Gets the News entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the News entity.
   */
  public function getCreatedTime();

  /**
   * Sets the News entity creation timestamp.
   *
   * @param int $timestamp
   *   The News entity creation timestamp.
   *
   * @return \Drupal\admin_news\Entity\NewsEntityInterface
   *   The called News entity entity.
   */
  public function setCreatedTime($timestamp);

}
