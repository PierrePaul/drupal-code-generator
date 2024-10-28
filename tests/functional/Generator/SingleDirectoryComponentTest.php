<?php

declare(strict_types=1);

namespace DrupalCodeGenerator\Tests\Functional\Generator;

use DrupalCodeGenerator\Command\SingleDirectoryComponent;
use DrupalCodeGenerator\Test\Functional\GeneratorTestBase;

/**
 * Tests Single Directory Component generator.
 */
final class SingleDirectoryComponentTest extends GeneratorTestBase {

  protected string $fixtureDir = __DIR__ . '/_sdc';

  /**
   * Test callback.
   */
  public function testGenerator(): void {

    $input = [
      'foo',
      'Foo',
      'Bar',
      'bar',
      'Some description.',
      'core/drupal',
      '',
      'Yes',
      'Yes',
      'Yes',
      'CTA text',
      'cta_text',
      'A text for CTA button.',
      'String',
      'No',
      'Yes',
      'My slot',
      '',
      'Some description for slot.',
      'No'
    ];
    $this->execute(SingleDirectoryComponent::class, $input);

    $expected_display = <<< 'TXT'
    
     Welcome to sdc generator!
    –––––––––––––––––––––––––––
    
     Theme machine name:
     ➤ foo

    
     Theme name [Foo]:
     ➤ 
    
     Component name:
     ➤ 
    
     Component machine name [bar]:
     ➤ 
    
     Component description (optional):
     ➤ 
    
     Library dependencies (optional). [Example: core/once]:
     ➤ core/drupal

     Library dependencies (optional). [Example: core/once]:
     ➤ 
    
     Need CSS? [Yes]:
     ➤ 
    
     Need JS? [Yes]:
     ➤ 
    
     Need component props? [Yes]:
     ➤ 

     Prop title:
     ➤ 

     Prop machine name [cta_text]:
     ➤ 

     Prop description (optional):
     ➤ 

     Prop type [String]:
      [1] String
      [2] Number
      [3] Boolean
      [4] Array
      [5] Object
      [6] Always null
     ➤ String


     Add another prop? [Yes]:
     ➤ 

     Need slots? [Yes]:
     ➤ 

     Slot title:
     ➤ 

     Slot machine name [my_slot]:
     ➤ 

     Slot description (optional):
     ➤ 

     Add another slot? [Yes]:
     ➤ 

     The following directories and files have been created or updated:
    –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––
     • components/bar/bar.component.yml
     • components/bar/bar.css
     • components/bar/bar.js
     • components/bar/bar.twig
     • components/bar/README.md
     • components/bar/thumbnail.jpg

    TXT;
    $this->assertDisplay($expected_display);

    $this->assertGeneratedFile('components/bar/bar.component.yml');
    $this->assertGeneratedFile('components/bar/bar.css');
    $this->assertGeneratedFile('components/bar/bar.js');
    $this->assertGeneratedFile('components/bar/bar.twig');
    $this->assertGeneratedFile('components/bar/README.md');
    $this->assertGeneratedFile('components/bar/thumbnail.jpg');
  }

}
