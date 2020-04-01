<?php

namespace Drupal\admin_points_extras;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Points extras entity entity.
 *
 * @see \Drupal\admin_points_extras\Entity\PointsExtrasEntity.
 */
class PointsExtrasEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\admin_points_extras\Entity\PointsExtrasEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished points extras entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published points extras entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit points extras entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete points extras entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add points extras entity entities');
  }

}
