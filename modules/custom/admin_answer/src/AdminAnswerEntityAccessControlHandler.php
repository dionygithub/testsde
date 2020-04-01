<?php

namespace Drupal\admin_answer;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Admin answer entity entity.
 *
 * @see \Drupal\admin_answer\Entity\AdminAnswerEntity.
 */
class AdminAnswerEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\admin_answer\Entity\AdminAnswerEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished admin answer entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published admin answer entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit admin answer entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete admin answer entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add admin answer entity entities');
  }

}
