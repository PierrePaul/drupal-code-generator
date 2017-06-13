<?php

namespace DrupalCodeGenerator\Helper;

use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Generator input handler.
 */
class InputHandler extends Helper {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'dcg_input_handler';
  }

  /**
   * Interact with the user and create variables for Twig templates.
   *
   * @param \Symfony\Component\Console\Input\InputInterface $input
   *   Input instance.
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   *   Output instance.
   * @param \Symfony\Component\Console\Question\Question[] $questions
   *   List of questions that the user should answer.
   *
   * @return array
   *   Template variables.
   */
  public function collectVars(InputInterface $input, OutputInterface $output, array $questions) {

    $vars = [];

    if ($answers_raw = $input->getOption('answers')) {
      $answers = json_decode($answers_raw, TRUE);
      if (!is_array($answers)) {
        throw new InvalidOptionException('Answers should be encoded in JSON format.');
      }
    }

    // Normalize questions.
    $questions = array_map(function ($question) {
      // Support array syntax.
      if (is_array($question)) {
        if (count($question) > 2) {
          throw new \OutOfBoundsException('The question array is too long.');
        }
        list($question_text, $default_value) = array_pad($question, 2, NULL);
        $question = new Question($question_text, $default_value);
      }
      return $question;
    }, $questions);

    // Let third party applications modify these questions.
    if ($this->getHelperSet()->has('dcg_input_preprocessor')) {
      $this->getHelperSet()->get('dcg_input_preprocessor')->preprocess($questions, $this);
    }

    /** @var \DrupalCodeGenerator\Command\GeneratorInterface $command */
    $command = $this->getHelperSet()->getCommand();
    $directory = $command->getDirectory();

    foreach ($questions as $name => $question) {
      /** @var \Symfony\Component\Console\Question\Question $question */
      $default_value = $question->getDefault();

      // Make some assumptions based on question name.
      if ($default_value === NULL) {
        switch ($name) {
          case 'name':
            $root_directory = basename(Utils::getExtensionRoot($directory) ?: $directory);
            $default_value = Utils::machine2human($root_directory);
            break;

          case 'machine_name':
            $default_value = function (array $vars) use ($directory) {
              return Utils::human2machine(isset($vars['name']) ? $vars['name'] : basename($directory));
            };
            break;
        }
      }

      if (is_callable($default_value)) {
        // Do not treat simple strings as callable because they may match PHP
        // builtin functions.
        if (!is_string($default_value) || strpos('::', $default_value) !== FALSE) {
          $default_value = call_user_func($default_value, $vars);
        }
      }
      $question->__construct($question->getQuestion(), $default_value);

      $error = FALSE;
      do {
        // Do not ask if valid answer was passed through command line arguments.
        if (!$error && isset($answers[$name])) {
          $answer = $answers[$name];
        }
        else {
          $this->formatQuestionText($question);
          /** @var \Symfony\Component\Console\Helper\QuestionHelper $question_helper */
          $question_helper = $this->getHelperSet()->get('question');
          $answer = $question_helper->ask($input, $output, $question);
          $error = FALSE;
        }
      } while ($error);

      $vars[$name] = $answer;
    }

    return $vars;
  }

  /**
   * Formats question text.
   *
   * @param \Symfony\Component\Console\Question\Question $question
   *   The question.
   */
  protected function formatQuestionText(Question $question) {
    $default_value = $question->getDefault();
    $question_text = $question->getQuestion();

    // Format question text.
    $question_text = "<info>$question_text</info>";
    if (is_bool($default_value)) {
      $default_value = $default_value ? 'Yes' : 'No';
    }
    if ($default_value) {
      $question_text .= " [<comment>$default_value</comment>]";
    }
    $question_text .= ': ';
    $question->__construct($question_text, $question->getDefault());
  }

}
