<?php

namespace Drupal\admin_test;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Admin test entity entity.
 *
 * @see \Drupal\admin_test\Entity\AdminTestEntity.
 */
class AdminTestEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\admin_test\Entity\AdminTestEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished admin test entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published admin test entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit admin test entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete admin test entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add admin test entity entities');
  }

}
