<?php

declare(strict_types=1);

namespace Drupal\Tests\plantain\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests library definitions.
 *
 * @group DCG
 */
final class LibrariesTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['plantain'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->container->get('theme_installer')->install(['shreya']);
  }

  /**
   * Test callback.
   */
  public function testLibraries(): void {
    // @todo Remove this once we drop support for Drupal 10.2.
    if (\version_compare(\Drupal::VERSION, '10.3', '<')) {
      self::markTestSkipped();
    }
    $libraries = $this->container->get('library.discovery')
      ->getLibrariesByExtension('shreya');
    self::assertSame(self::getExpectedLibraries(), $libraries);
  }

  /**
   * Returns expected library definitions.
   */
  private static function getExpectedLibraries(): array {
    return [
      'global' =>
        [
          'js' =>
            [
              [
                'group' => -100,
                'type' => 'file',
                'data' => 'themes/shreya/js/shreya.js',
                'version' => -1,
                'minified' => FALSE,
              ],
            ],
          'css' =>
            [
              [
                'weight' => -200,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/base/elements.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/block.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/breadcrumb.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/field.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/form.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/header.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/menu.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/messages.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/node.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/sidebar.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/table.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/tabs.css',
                'version' => -1,
              ],
              [
                'weight' => 0,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/components/buttons.css',
                'version' => -1,
              ],
              [
                'weight' => -100,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/layouts/layout.css',
                'version' => -1,
              ],
              [
                'media' => 'print',
                'weight' => 200,
                'group' => 100,
                'type' => 'file',
                'data' => 'themes/shreya/css/theme/print.css',
                'version' => -1,
              ],
            ],
          'dependencies' => [],
          'license' =>
            [
              'name' => 'GPL-2.0-or-later',
              'url' => 'https://www.drupal.org/licensing/faq',
              'gpl-compatible' => TRUE,
            ],
        ],
    ];
  }

}
