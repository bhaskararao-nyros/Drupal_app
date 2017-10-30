<?php

namespace Drupal\stacks\WidgetAdmin\Validator;

/**
 * Class BaseValidator.
 * @package Drupal\stacks\WidgetAdmin\Validator
 */
abstract class BaseValidator implements ValidatorInterface {

  protected $errorMessage;

  /**
   * BaseValidator constructor.
   * @param $error_message
   */
  public function __construct($error_message) {
    $this->errorMessage = $error_message;
  }

  /**
   * @inheritDoc
   */
  public function getErrorMessage() {
    return $this->errorMessage;
  }

}