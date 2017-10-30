<?php

namespace Drupal\stacks\WidgetAdmin\Validator;

/**
 * Class ValidatorRequired.
 * @package Drupal\stacks\WidgetAdmin\Validator
 */
class ValidatorRequired extends BaseValidator {

  /**
   * @inheritDoc
   */
  public function validates($value) {
    return is_array($value) ? !empty(array_filter($value)) : !empty($value);
  }

}
