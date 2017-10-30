<?php

namespace Drupal\stacks\WidgetAdmin\Validator;

/**
 * Interface ValidatorInterface.
 * @package Drupal\stacks\WidgetAdmin\Validator
 */
interface ValidatorInterface {

  /**
   * Returns bool indicating if validation is ok.
   */
  public function validates($value);

  /**
   * Returns error message.
   */
  public function getErrorMessage();

}