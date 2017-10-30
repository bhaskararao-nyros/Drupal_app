<?php

namespace Drupal\stacks\WidgetAdmin\Button;

use Drupal\stacks\WidgetAdmin\Step\StepsEnum;

/**
 * Class StepTwoFinishButton.
 * @package Drupal\stacks\WidgetAdmin\Button
 */
class StepTwoFinishButton extends BaseButton {

  /**
   * @inheritDoc.
   */
  public function getKey() {
    return 'finish';
  }

  /**
   * @inheritDoc.
   */
  public function build() {
    return [
      '#type' => 'submit',
      '#value' => t('Add Widget'),
      '#goto_step' => StepsEnum::STEP_FINALIZE,
      '#submit_handler' => 'submitValues',
    ];
  }

  /**
   * @inheritDoc.
   */
  public function getSubmitHandler() {
    return 'submitIntake';
  }

}
