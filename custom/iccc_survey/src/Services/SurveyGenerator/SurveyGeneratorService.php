<?php

declare(strict_types=1);

namespace Drupal\iccc_survey\Services\SurveyGenerator;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * @todo Add class description.
 */
final class SurveyGeneratorService implements SurveyGeneratorServiceInterface {

  /**
   * Constructs a SurveyGeneratorService object.
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {}




  /**
   * {@inheritdoc}
   */
  public function doSomething(): void {
    // @todo Place your code here.
  }


    /**
   *
     *
     * This code is put here as an example to generate an entity from another
     * entity then update post creation
   */
//  public function createSurvey(string $tabViewId)
//  {
//    $tabView = TabView::load($tabViewId);
//    $revisionService = new RevisionService();
//    $returnValue = $revisionService->getTabView($tabView);
//
//
//    $survey = Survey::create(array(
//        'name' => $tabView->getName(),
//        'obd_id' => $tabView->getObdId(),
//        'language' => 'en',
//        'user_id' => 1,
//        'version' => '1',
//        'valid' => '1',
//        'json_string' => '',
//        'revision_status' => '1',
//        'status' => '1',
//      )
//    );
//
//    // the survey needs its own entity id stored in the JSON string
//    $entityId = $survey->save();
//    $returnValue['entityId'] = $survey->id();
//    $surveyJsonString = json_encode($returnValue);
//    $survey->setJsonString($surveyJsonString);
//    $survey->setRevisionStatus('Draft');
//
//    \Drupal::logger('content')->notice($survey->id());
//
//    // update the survey
//    $entityId = $survey->save();
//    return $entityId;
//  }


}
