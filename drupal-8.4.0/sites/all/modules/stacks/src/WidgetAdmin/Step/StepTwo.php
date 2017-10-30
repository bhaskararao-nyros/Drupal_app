<?php

namespace Drupal\stacks\WidgetAdmin\Step;

use Drupal\stacks\WidgetAdmin\Button\StepTwoFinishButton;
use Drupal\stacks\WidgetAdmin\Button\StepTwoFinishEditButton;
use Drupal\stacks\WidgetAdmin\Button\StepTwoPreviousButton;
use Drupal\stacks\Widget\WidgetData;

class StepTwo extends BaseStep {

  private $skipped_step_1 = FALSE;

  /**
   * @inheritDoc.
   */
  public function setStep() {
    return StepsEnum::STEP_TWO;
  }

  /**
   * @inheritDoc.
   */
  public function getButtons() {
    $buttons = [];

    if (!$this->skippedStep1()) {
      $buttons[] = new StepTwoPreviousButton();
    }

    // Change the finish button if they are modifying an instance.
    $finish_button = new StepTwoFinishButton();
    $step1_values = $this->getStepValues(1);
    if (!empty($step1_values['widget_instance_id'])) {
      $finish_button = new StepTwoFinishEditButton();
    }

    $buttons[] = $finish_button;
    return $buttons;
  }

  /**
   * @inheritDoc.
   */
  public function buildStepFormElements() {
    $step1_values = $this->getStepValues(1);

    // Define whether we are adding or updating.
    $add_entity = TRUE;
    if (!empty($step1_values['widget_instance_id'])) {
      $add_entity = FALSE;
    }

    $form = [];

    $type_of_widget = 'basic';
    if ($step1_values['widget_type'] == 'contentfeed' && \Drupal::moduleHandler()
        ->moduleExists('stacks_content_feed')
    ) {
      $type_of_widget = 'contentfeed';
      $form['#attached']['library'][] = 'stacks_content_feed/admin.content_feed_forms';
    }

    // Attach the code to convert drop downs to tabs for Inline Entity Form
    /*
        $IEF_field = WidgetData::getIEFFieldName($step1_values['bundle']);
        if (!empty($IEF_field)) {
          $type_of_widget = 'ief_tabs';
          $form['#attached']['library'][] = 'stacks/admin_ief_tabs';
          $IEF_field = WidgetData::getIEFFieldName($step1_values['bundle']);
          $IEF_field = str_replace('_', '-', $IEF_field);
          $form['#attached']['drupalSettings']['stacks_clist_settings']['reference_field_name'] = $IEF_field;
          $IEF_field_bundles = WidgetData::getIEFFieldTabBundles($step1_values['bundle']);
          //$form['#attached']['drupalSettings']['stacks_clist_settings']['reference_field_bundles'] = $IEF_field_bundles;
          $form['ief_tabs_bundles'] = array('#type' => 'hidden', '#value' => json_encode($IEF_field_bundles));
        }
    */
    $form['widget_type'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => WidgetData::getWidgetType_fromwidget($step1_values['bundle']),
      '#attributes' => [
        'class' => [
          'widget_type_list'
        ],
      ],
    ];

    if ($add_entity) {
      $form['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => t('Add') . ': ' . $step1_values['widget_name'],
        '#attributes' => [
          'class' => [
            'widget-title'
          ],
        ],
      ];
    }
    else {
      $form['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => t('Update') . ': ' . $step1_values['widget_name'],
        '#attributes' => [
          'class' => [
            'widget-title'
          ],
        ],
      ];
    }

    $form['widget_instance_id'] = [
      '#type' => 'hidden',
      '#value' => (isset($_GET['widget_instance_id']) ? (int) $_GET['widget_instance_id'] : 0)
    ];

    $form['widget_name'] = [
      '#type' => 'textfield',
      '#title' => t('Widget Name'),
      '#placeholder' => t('Widget Name'),
      '#default_value' => $step1_values['widget_name'],
      '#required' => FALSE,
      '#prefix' => '<div class="step_2_wrapper_widget_name">',
      '#suffix' => '</div>',
    ];


    // Load the widget_entity bundle entity form.
    $form['inline_entity_form'] = [
      '#ief_id' => 'widget_entity_form',
      '#type' => 'inline_entity_form',
      '#entity_type' => 'widget_entity',
      '#bundle' => $step1_values['bundle'],
      '#op' => 'add',
      '#save_entity' => TRUE,
      '#ief_row_delta' => $step1_values['delta'],
      '#prefix' => '<div class="step_2_wrapper type_' . $type_of_widget . '">',
      '#suffix' => '</div>',
    ];

    // Are we loading an entity?
    if (isset($this->getValues()['inline_entity_form'])) {
      $form['inline_entity_form']['#op'] = 'edit';

      // Attach the stacks entity to the inline entity form.
      $form['inline_entity_form']['#default_value'] = $this->getValues()['inline_entity_form'];
    }

    // Add developer settings if this use has the right permissions.
    $account = \Drupal::currentUser();
    if ($account->hasPermission('developer')) {
      $form['developer_settings'] = [
        '#prefix' => '<div class="developer-settings">',
        '#type' => 'details',
        '#title' => t('Developer Settings'),
        '#open' => FALSE,
        '#suffix' => '</div>',
      ];

      $form['developer_settings']['wrapper_id'] = [
        '#type' => 'textfield',
        '#title' => t('Wrapper ID'),
        '#placeholder' => t('Wrapper ID'),
        '#description' => t('This value is used in the wrapper container of this widget as the ID attribute. Leave off the "#" sign.'),
        '#default_value' => isset($this->getValues()['wrapper_id']) ? $this->getValues()['wrapper_id'] : '',
        '#required' => FALSE,
      ];

      $form['developer_settings']['wrapper_classes'] = [
        '#type' => 'textfield',
        '#title' => t('Wrapper Classes'),
        '#placeholder' => t('Wrapper Classes'),
        '#description' => t('This value is used in the wrapper container of this widget as the class attribute. Separate multiple values with a comma.'),
        '#default_value' => isset($this->getValues()['wrapper_classes']) ? $this->getValues()['wrapper_classes'] : '',
        '#required' => FALSE,
      ];
    }

    return $form;
  }

  /**
   * Pre-populates info from the widget instance.
   */
  public function editWidgetInstance($entities) {
    $widget_instance = $entities['widget_instance_entity'];
    $widget_entity = $entities['widget_entity'];

    $default_values = [
      'inline_entity_form' => $widget_entity,
      'wrapper_id' => $widget_instance->getWrapperID(),
      'wrapper_classes' => $widget_instance->getWrapperClasses(),
    ];

    // Now save the values for this step. This should pre-populate the form.
    $this->setValues($default_values);
  }

  /**
   * @inheritDoc.
   */
  public function getFieldNames() {
    return [
      'inline_entity_form',
      'wrapper_id',
      'wrapper_classes',
    ];
  }

  /**
   * @inheritDoc.
   */
  public function getFieldsValidators() {
    return [];
  }

  /**
   * Sets that step #1 was skipped.
   */
  public function setSkippedStep1() {
    $this->skipped_step_1 = TRUE;
  }

  /**
   * Returns whether step #1 was skipped.
   *
   * @return bool
   */
  public function skippedStep1() {
    return $this->skipped_step_1;
  }

}
