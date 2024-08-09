<?php

declare(strict_types=1);

namespace Drupal\survey_documents\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\File\FileSystemInterface;;
use Drupal\survey_documents\SurveyDocumentsInterface;
use Drupal\user\EntityOwnerTrait;
use Drupal\user\UserInterface;

/**
 * Defines the survey documents entity class.
 *
 * @ContentEntityType(
 *   id = "survey_documents",
 *   label = @Translation("Survey Documents"),
 *   label_collection = @Translation("Survey Documentss"),
 *   label_singular = @Translation("survey documents"),
 *   label_plural = @Translation("survey documentss"),
 *   label_count = @PluralTranslation(
 *     singular = "@count survey documentss",
 *     plural = "@count survey documentss",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\survey_documents\SurveyDocumentsListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\survey_documents\SurveyDocumentsAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\survey_documents\Form\SurveyDocumentsForm",
 *       "edit" = "Drupal\survey_documents\Form\SurveyDocumentsForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "survey_documents",
 *   data_table = "survey_documents_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer survey_documents",
 *   entity_keys = {
 *     "id" = "id",
 *     "langcode" = "langcode",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/survey-documents",
 *     "add-form" = "/survey-documents/add",
 *     "canonical" = "/survey-documents/{survey_documents}",
 *     "edit-form" = "/survey-documents/{survey_documents}/edit",
 *     "delete-form" = "/survey-documents/{survey_documents}/delete",
 *     "delete-multiple-form" = "/admin/content/survey-documents/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.survey_documents.settings",
 * )
 */
final class SurveyDocuments extends ContentEntityBase implements SurveyDocumentsInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  public function getLabel(): string {
  return $this->get('label')->value;
}

public function setLabel(string $label): self {
  $this->set('label', $label);
  return $this;
}

public function getStatus(): bool {
  return (bool) $this->get('status')->value;
}

public function setStatus(bool $status): self {
  $this->set('status', $status);
  return $this;
}

public function getNotes(): string {
  return $this->get('notes')->value;
}

public function setNotes(string $notes): self {
  $this->set('notes', $notes);
  return $this;
}



public function getCreatedTime(): string {
  return $this->get('created')->value;
}

public function getChangedTime(): string {
  return $this->get('changed')->value;
}

public function getReportId(): string {
  return (int) $this->get('reportId')->value;
}

public function setReportId(int $reportId): self {
  $this->set('reportId', $reportId);
  return $this;
}

public function isVisible(): bool {
  return (bool) $this->get('visible')->value;
}

public function setVisible(bool $visible): self {
  $this->set('visible', $visible);
  return $this;
}

public function getStepId(): string {
  return $this->get('stepId')->value;
}

public function setStepId(string $stepId): self {
  $this->set('stepId', $stepId);
  return $this;
}


  /**
   * Get the file entity ID.
   *
   * @return int|null
   *   The file entity ID or NULL if no file is associated.
   */
  public function getFileId(): ?int {
    $file = $this->get('file')->entity;
    return $file ? (int) $file->id() : null;
  }


  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setTranslatable(TRUE)
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['notes'] = BaseFieldDefinition::create('text_long')
      ->setTranslatable(TRUE)
      ->setLabel(t('Notes'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setTranslatable(TRUE)
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the survey documents was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the survey documents was last edited.'));

    $fields['reportId'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Report ID'))
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['visible'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Visible'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['stepId'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Step ID'))
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['file'] = BaseFieldDefinition::create('file')
      ->setLabel(t('File'))
      ->setDescription(t('Upload a file'))
      ->setSettings([
        'file_directory' => 'private://survey_documents',
        'file_extensions' => 'doc xls pdf ppt pps odt ods odp txt mp3 mov mpg flv m4v mp4 ogg ovg wmv png gif jpg jpeg ico',
        'max_filesize' => '',
        'handler' => 'default:file',
      ])
      ->setDisplayOptions('form', [
        'type' => 'file',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
