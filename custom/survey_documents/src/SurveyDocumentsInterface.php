<?php

declare(strict_types=1);

namespace Drupal\survey_documents;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a survey documents entity type.
 */
interface SurveyDocumentsInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
