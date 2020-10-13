<?php

namespace DrupalCodeGenerator\Test;

use DrupalCodeGenerator\Application;
use DrupalCodeGenerator\Helper\Renderer;
use DrupalCodeGenerator\Tests\QuestionHelper;
use DrupalCodeGenerator\Twig\TwigEnvironment;
use DrupalCodeGenerator\Utils;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Tester\TesterTrait;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Loader\FilesystemLoader;

/**
 * Base class for generator tests.
 */
abstract class BaseGeneratorTest extends TestCase {

  use TesterTrait;

  protected $fixtureDir;
  private $directory;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    $this->directory = \sys_get_temp_dir() . '/dcg_sandbox';
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown(): void {
    (new Filesystem())->remove($this->directory);
  }

  /**
   * Executes the command.
   *
   * @param \Symfony\Component\Console\Command\Command $command
   *   A command to execute.
   * @param array $user_input
   *   An array of strings representing each input passed to the command input
   *   stream.
   */
  protected function execute(Command $command, array $user_input): void {

    $input = [
      'command' => $command->getName(),
      '--destination' => $this->directory,
      '--working-dir' => $this->directory,
    ];

    $this->input = new ArrayInput($input);
    $this->input->setStream(self::createStream($user_input));
    $this->output = new StreamOutput(\fopen('php://memory', 'w'));

    $application = $this->createApplication();
    $application->add($command);
    $application->run($this->input, $this->output);
  }

  /**
   * Asserts generated display.
   */
  protected function assertDisplay(string $expected_display): void {
    $default_name = Utils::machine2human(\basename($this->directory), TRUE);
    $expected_display = \str_replace('%default_name%', $default_name, $expected_display);
    self::assertEquals($expected_display, $this->getDisplay());
  }

  /**
   * Asserts generated file.
   */
  protected function assertGeneratedFile(string $file, string $fixture): void {
    self::assertFileEquals($this->fixtureDir . '/' . $fixture, $this->directory . '/' . $file);
  }

  /**
   * Creates DCG application.
   */
  private function createApplication(): Application {
    $application = Application::create();
    $application->setAutoExit(FALSE);

    $helper_set = $application->getHelperSet();

    // Replace default question helper to ease parsing output.
    $helper_set->set(new QuestionHelper());

    // Replace default renderer to support 'strict_variables' in tests.
    $twig_environment = new TwigEnvironment(new FilesystemLoader(), ['strict_variables' => TRUE]);
    $helper_set->set(new Renderer($twig_environment));
    return $application;
  }

}
