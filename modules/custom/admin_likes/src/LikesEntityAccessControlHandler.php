<?php

namespace Drupal\admin_likes;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Likes entity entity.
 *
 * @see \Drupal\admin_likes\Entity\LikesEntity.
 */
class LikesEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\admin_likes\Entity\LikesEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished likes entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published likes entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit likes entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete likes entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add likes entity entities');
  }

}
