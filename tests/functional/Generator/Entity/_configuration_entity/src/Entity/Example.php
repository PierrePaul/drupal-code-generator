<?php

declare(strict_types=1);

namespace Drupal\foo\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\Attribute\ConfigEntityType;
use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\foo\ExampleInterface;
use Drupal\foo\ExampleListBuilder;
use Drupal\foo\Form\ExampleForm;

/**
 * Defines the example entity type.
 */
#[ConfigEntityType(
  id: 'example',
  label: new TranslatableMarkup('Example'),
  label_collection: new TranslatableMarkup('Examples'),
  label_singular: new TranslatableMarkup('example'),
  label_plural: new TranslatableMarkup('examples'),
  config_prefix: 'example',
  entity_keys: [
    'id' => 'id',
    'label' => 'label',
    'uuid' => 'uuid',
  ],
  handlers: [
    'list_builder' => ExampleListBuilder::class,
    'form' => [
      'add' => ExampleForm::class,
      'edit' => ExampleForm::class,
      'delete' => EntityDeleteForm::class,
    ],
  ],
  links: [
    'collection' => '/admin/structure/example',
    'add-form' => '/admin/structure/example/add',
    'edit-form' => '/admin/structure/example/{example}',
    'delete-form' => '/admin/structure/example/{example}/delete',
  ],
  admin_permission: 'administer example',
  label_count: [
    'singular' => '@count example',
    'plural' => '@count examples',
  ],
  config_export: [
    'id',
    'label',
    'description',
  ],
)]
final class Example extends ConfigEntityBase implements ExampleInterface {

  /**
   * The example ID.
   */
  protected string $id;

  /**
   * The example label.
   */
  protected string $label;

  /**
   * The example description.
   */
  protected string $description;

}
