<?php

namespace Drupal\admin_question;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Admin question entity entity.
 *
 * @see \Drupal\admin_question\Entity\AdminQuestionEntity.
 */
class AdminQuestionEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\admin_question\Entity\AdminQuestionEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished admin question entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published admin question entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit admin question entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete admin question entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add admin question entity entities');
  }

}
