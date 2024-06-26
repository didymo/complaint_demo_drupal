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


  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Survey name.
   *
   * @return string
   *   Name of the Survey.
   */
  public function getName();

  /**
   * Sets the Survey name.
   *
   * @param string $name
   *   The Survey name.
   *
   * @return SurveyInterface
   *   The called Survey entity.
   */
  public function setName(string $name): SurveyInterface;

  /**
   * Gets the Survey ObdId.
   *
   * @return string
   *   The Survey ObdId.
   */
  public function getObdId();

  /**
   * Sets the Survey ObdId.
   *
   * @param string $obdId
   *   The Survey ObdId.
   *
   * @return SurveyInterface
   *   The called Survey entity.
   */
  public function setObdId($obdId);


  /**
   * Gets the Json String.
   *
   * @return string
   *   The Json String.
   */
  public function getJsonString(): string;

  /**
   * Sets the Json String.
   *
   * @param string $jsonString
   *   The Json String.
   *
   * @return SurveyInterface
   *   The called Survey entity.
   */
  public function setJsonString(string $jsonString): SurveyInterface;


    /**
   * Returns the current revision status.
   *
   * @return string
   *   Returns the human readable name of the revision status.
   */
  public function getRevisionStatus();

  /**
   * Sets the revision using the human readable name of the taxonomy term.
   *
   * @param string $term_name
   *   The human readable name of the revision status.
   * @return SurveyInterface
   *   The called Survey entity.
   */
  public function setRevisionStatus($term_name);

  /**
   * Gets the Survey creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Survey.
   */
  public function getCreatedTime();

  /**
   * Sets the Survey creation timestamp.
   *
   * @param int $timestamp
   *   The Survey creation timestamp.
   *
   * @return SurveyInterface
   *   The called Survey entity.
   */
  public function setCreatedTime($timestamp);


  /**
   * Gets the Survey revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Survey revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return SurveyInterface
   *   The called Survey entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Survey revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Survey revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return SurveyInterface
   *   The called Survey entity.
   */
  public function setRevisionUserId($uid);

}
