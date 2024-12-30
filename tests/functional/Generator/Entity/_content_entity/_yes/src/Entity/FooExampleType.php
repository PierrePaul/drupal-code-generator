<?php

declare(strict_types=1);

namespace Drupal\foo\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\Attribute\ConfigEntityType;
use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\foo\FooExampleTypeListBuilder;
use Drupal\foo\Form\FooExampleTypeForm;

/**
 * Defines the Example type configuration entity.
 */
#[ConfigEntityType(
  id: 'foo_example_type',
  label: new TranslatableMarkup('Example type'),
  label_collection: new TranslatableMarkup('Example types'),
  label_singular: new TranslatableMarkup('example type'),
  label_plural: new TranslatableMarkup('examples types'),
  config_prefix: 'foo_example_type',
  entity_keys: [
    'id' => 'id',
    'label' => 'label',
    'uuid' => 'uuid',
  ],
  handlers: [
    'list_builder' => FooExampleTypeListBuilder::class,
    'route_provider' => [
      'html' => AdminHtmlRouteProvider::class,
    ],
    'form' => [
      'add' => FooExampleTypeForm::class,
      'edit' => FooExampleTypeForm::class,
      'delete' => EntityDeleteForm::class,
    ],
  ],
  links: [
    'add-form' => '/admin/structure/foo_example_types/add',
    'edit-form' => '/admin/structure/foo_example_types/manage/{foo_example_type}',
    'delete-form' => '/admin/structure/foo_example_types/manage/{foo_example_type}/delete',
    'collection' => '/admin/structure/foo_example_types',
  ],
  admin_permission: 'administer foo_example types',
  bundle_of: 'foo_example',
  label_count: [
    'singular' => '@count example type',
    'plural' => '@count examples types',
  ],
  config_export: [
    'id',
    'label',
    'uuid',
  ],
)]
final class FooExampleType extends ConfigEntityBundleBase {

  /**
   * The machine name of this example type.
   */
  protected string $id;

  /**
   * The human-readable name of the example type.
   */
  protected string $label;

}
