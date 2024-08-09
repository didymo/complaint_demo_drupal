<?php

declare(strict_types=1);

namespace Drupal\survey_documents\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\survey_documents\Entity\SurveyDocuments;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;

/**
 * Represents Get Survey Document List records as resources.
 *
 * @RestResource (
 *   id = "get_survey_document_list",
 *   label = @Translation("Get Survey Document List"),
 *   uri_paths = {
 *     "canonical" = "/api/get-survey-document-list/{reportId}",
 *     "create" = "/api/get-survey-document-list"
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
final class GetSurveyDocumentListResource extends ResourceBase {

  /**
   * The key-value storage.
   */
  private readonly KeyValueStoreInterface $storage;

    /**
   * The current user.
   */
  private AccountProxyInterface $currentUser;

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
      AccountProxyInterface    $currentUser,
    EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->storage = $keyValueFactory->get('get_survey_document_list');
    $this->currentUser = $currentUser;
     $this->entityTypeManager = $entityTypeManager;
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
       $container->get('current_user'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Responds to GET requests.
   */
  public function get($reportId): ResourceResponse {

        if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

//    $reportId = 123; // Replace with the actual reportId you want to filter by.


//    $query = \Drupal::entityTypeManager('survey_douments')
//      ->condition('status', 1) // 1 indicates published.
//    ->condition('reportId', $reportId)
//      ->accessCheck(TRUE); // Enable access checks.

    // Create an entity query for the survey_documents entity.
    $query = $this->entityTypeManager
      ->getStorage('survey_documents') // Get the storage handler.
      ->getQuery() // Create the query.
      ->condition('status', 1) // Add condition for published documents.
      ->condition('reportId', $reportId) // Add condition for matching reportId.
     ->accessCheck(TRUE); // Enable access checks.

    $surveyDocumentIds = $query->execute();
    $unformattedSurveyDocuments = SurveyDocuments::loadMultiple($surveyDocumentIds);
        $surveyDocumentList = array();

        foreach ($unformattedSurveyDocuments as $unformattedSurveyDocument) {
          if ($unformattedSurveyDocument instanceof SurveyDocuments) {
            $document['label'] = $unformattedSurveyDocument->getLabel();
            $document['entityId'] = $unformattedSurveyDocument->id();
            $document['stepId'] = $unformattedSurveyDocument->getStepId();
            $document['fileEntityId'] = $unformattedSurveyDocument->getFileId();

            $surveyDocumentList[] = $document;
            unset($document);
          }
        }


        $response = new ResourceResponse($surveyDocumentList);
        $response->addCacheableDependency($this->currentUser);
        return $response;
  }

  /**
   * {@inheritdoc}
   */
  protected function getBaseRoute($canonical_path, $method): Route {
    $route = parent::getBaseRoute($canonical_path, $method);
    // Set ID validation pattern.
    if ($method !== 'POST') {
      $route->setRequirement('id', '\d+');
    }
    return $route;
  }

  /**
   * Returns next available ID.
   */
  private function getNextId(): int {
    $ids = \array_keys($this->storage->getAll());
    return count($ids) > 0 ? max($ids) + 1 : 1;
  }

}
