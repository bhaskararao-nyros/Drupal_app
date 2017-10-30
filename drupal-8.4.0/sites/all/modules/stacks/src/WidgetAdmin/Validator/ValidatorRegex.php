<?php

namespace Drupal\stacks\WidgetAdmin\Validator;

/**
 * Class ValidatorRegex.
 * @package Drupal\stacks\WidgetAdmin\Validator
 */
class ValidatorRegex extends BaseValidator {

  protected $pattern;

  /**
   * ValidatorRegex constructor.
   * @param $error_message
   * @param $pattern
   */
  public function __construct($error_message, $pattern) {
    parent::__construct($error_message);
    $this->pattern = $pattern;
  }

  /**
   * @inheritDoc
   */
  public function validates($value) {
    return preg_match($this->pattern, $value);
  }

}
