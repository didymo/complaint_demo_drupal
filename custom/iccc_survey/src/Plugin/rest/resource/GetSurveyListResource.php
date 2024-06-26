<?php

declare(strict_types=1);

namespace Drupal\iccc_survey\Plugin\rest\resource;

use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\iccc_survey\Entity\Survey;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;

/**
 * Represents get_survey_list records as resources.
 *
 * @RestResource (
 *   id = "get_survey_list_resource",
 *   label = @Translation("Get survey list resource"),
 *   uri_paths = {
 *     "canonical" = "/rest/survey/list",
 *     "create" = "/rest/survey/list"
 *   }
 * )
 *
 * @DCG
 * The plugin exposes key-value records as REST resources. In order to enable it
 * import the resource configuration into active configuration storage. An
 * example of such configuration can be located in the following file:
 * core/modules/rest/config/optional/rest.resource.entity.node.yml.
 * Alternatively, you can enable it through admin interface provider by REST UI
 * module.
 * @see https://www.drupal.org/project/restui
 *
 * @DCG
 * Notice that this plugin does not provide any validation for the data.
 * Consider creating custom normalizer to validate and normalize the incoming
 * data. It can be enabled in the plugin definition as follows.
 * @code
 *   serialization_class = "Drupal\foo\MyDataStructure",
 * @endcode
 *
 * @DCG
 * For entities, it is recommended to use REST resource plugin provided by
 * Drupal core.
 * @see \Drupal\rest\Plugin\rest\resource\EntityResource
 */
final class GetSurveyListResource extends ResourceBase {

  /**
   * The key-value storage.
   */
  private readonly KeyValueStoreInterface $storage;


  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    KeyValueFactoryInterface $keyValueFactory,
    AccountProxyInterface $currentUser,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->storage = $keyValueFactory->get('get_survey_list_resource');
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('keyvalue'),
      $container->get('current_user')
    );
  }

  /**
   * Responds to GET requests.
   *
   * @return ResourceResponse
   *    The HTTP response object.
   *
   */
  public function get()
  {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    $unformattedSurveys = Survey::loadMultiple();
    $surveyList = array();
    foreach ($unformattedSurveys as $unformattedSurvey) {
      if ($unformattedSurvey instanceof Survey) {
        $survey['label'] = $unformattedSurvey->getName();
        $survey['entityId'] = $unformattedSurvey->id();
        $survey['revisionId'] = $unformattedSurvey->getRevisionId();
        $survey['obdId'] = $unformattedSurvey->getObdId();
        $survey['revisionCreationTime'] = $unformattedSurvey->getRevisionCreationTime();
        $survey['revisionStatus'] = $unformattedSurvey->getRevisionStatus();

        $surveyList[] = $survey;
        unset($survey);
      }
    }

    $response = new ResourceResponse($surveyList);
    $response->addCacheableDependency($this->currentUser);
    return $response;
  }

}
