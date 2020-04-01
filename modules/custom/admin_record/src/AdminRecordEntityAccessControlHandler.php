<?php

namespace Drupal\admin_record;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Admin record entity entity.
 *
 * @see \Drupal\admin_record\Entity\AdminRecordEntity.
 */
class AdminRecordEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\admin_record\Entity\AdminRecordEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished admin record entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published admin record entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit admin record entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete admin record entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add admin record entity entities');
  }

}
