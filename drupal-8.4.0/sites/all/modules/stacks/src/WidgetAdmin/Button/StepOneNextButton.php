<?php

namespace Drupal\stacks\WidgetAdmin\Button;

use Drupal\stacks\WidgetAdmin\Step\StepsEnum;

/**
 * Class StepOneNextButton.
 * @package Drupal\stacks\WidgetAdmin\Button
 */
class StepOneNextButton extends BaseButton {

  /**
   * @inheritDoc.
   */
  public function getKey() {
    return 'next';
  }

  /**
   * @inheritDoc.
   */
  public function build() {
    $disabled = isset($_GET['widget_instance_id']) && is_numeric($_GET['widget_instance_id']) ? FALSE : TRUE;
    return [
      '#type' => 'submit',
      '#value' => t('Next'),
      '#goto_step' => StepsEnum::STEP_TWO,
      '#attributes' => [
        'disabled' => $disabled,
        'class' => ['button--primary'],
      ],
    ];
  }

}
