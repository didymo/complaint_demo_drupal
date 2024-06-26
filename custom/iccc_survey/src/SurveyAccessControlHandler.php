<?php

declare(strict_types=1);

namespace Drupal\iccc_survey;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the survey entity type.
 *
 * phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
 *
 * @see https://www.drupal.org/project/coder/issues/3185082
 */
final class SurveyAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account): AccessResult {
    if ($account->hasPermission($this->entityType->getAdminPermission())) {
      return AccessResult::allowed()->cachePerPermissions();
    }

    return match($operation) {
      'view' => AccessResult::allowedIfHasPermission($account, 'view iccc_survey_survey'),
      'update' => AccessResult::allowedIfHasPermission($account, 'edit iccc_survey_survey'),
      'delete' => AccessResult::allowedIfHasPermission($account, 'delete iccc_survey_survey'),
      'delete revision' => AccessResult::allowedIfHasPermission($account, 'delete iccc_survey_survey revision'),
      'view all revisions', 'view revision' => AccessResult::allowedIfHasPermissions($account, ['view iccc_survey_survey revision', 'view iccc_survey_survey']),
      'revert' => AccessResult::allowedIfHasPermissions($account, ['revert iccc_survey_survey revision', 'edit iccc_survey_survey']),
      default => AccessResult::neutral(),
    };
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL): AccessResult {
    return AccessResult::allowedIfHasPermissions($account, ['create iccc_survey_survey', 'administer iccc_survey_survey'], 'OR');
  }

}
