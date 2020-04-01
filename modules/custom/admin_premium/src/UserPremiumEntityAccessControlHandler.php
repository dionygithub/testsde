<?php

namespace Drupal\admin_premium;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the User premium entity entity.
 *
 * @see \Drupal\admin_premium\Entity\UserPremiumEntity.
 */
class UserPremiumEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\admin_premium\Entity\UserPremiumEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished user premium entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published user premium entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit user premium entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete user premium entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add user premium entity entities');
  }

}
