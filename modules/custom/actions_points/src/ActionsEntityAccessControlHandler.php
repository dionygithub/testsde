<?php

namespace Drupal\actions_points;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Actions entity entity.
 *
 * @see \Drupal\actions_points\Entity\ActionsEntity.
 */
class ActionsEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\actions_points\Entity\ActionsEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished actions entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published actions entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit actions entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete actions entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add actions entity entities');
  }

}
