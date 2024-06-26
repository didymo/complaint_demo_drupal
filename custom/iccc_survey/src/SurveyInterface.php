<?php

declare(strict_types=1);

namespace Drupal\iccc_survey;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a survey entity type.
 */
interface SurveyInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
