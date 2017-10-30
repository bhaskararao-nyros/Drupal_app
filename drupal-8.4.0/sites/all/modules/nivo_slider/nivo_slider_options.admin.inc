<?php

/**
 * @file
 * Generate configuration form and save settings.
 */

/**
 * Configuration form for slider options.
 */
function nivo_slider_option_configuration_form($form, &$form_state) {
  // Create a list of the currently available themes
  $themes = module_invoke_all('nivo_slider_theme_info');

  // Allow theme information to be altered
  drupal_alter('nivo_slider_theme_info', $themes);

  $config = config('nivo_slider.settings');
  $available_themes = array();

  foreach ($themes as $theme => $properties) {
    $available_themes[$theme] = $properties['name'];
  }

  // Theme
  $form['nivo_slider_theme'] = array(
    '#type' => 'select',
    '#title' => t('Theme'),
    '#options' => $available_themes,
    '#default_value' => $config->get('options.theme'),
    '#description' => t('Select a slider theme. The slider theme determines the overall appearance of the slider.'),
  );

  // Display image style settings if the image module is available
  if (module_exists('image') == TRUE) {
    // Create a list of the currently available image styles
    $image_styles = entity_load_multiple('image_style');
    $available_themes = array();

    foreach ($image_styles as $image_style) {
      $available_image_styles[$image_style->id()] = $image_style->label();
    }

    // Image Style
    $form['nivo_slider_image_style'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use image styles to generate slider images'),
      '#default_value' => $config->get('options.image_style'),
      '#description' => t('Easily modify, scale, crop and apply various effects to slider images using <a href="@image-styles">Image styles</a>.', array('@image-styles' => '/admin/config/media/image-styles')),
    );
    $form['nivo_slider_image_style_slide'] = array(
      '#type' => 'select',
      '#title' => t('Slide image style'),
      '#options' => $available_image_styles,
      '#default_value' => $config->get('options.image_style_slide'),
      '#description' => t('Select an image style to apply to slide images.'),
      '#states' => array(
        'visible' => array(
          ':input[name="nivo_slider_image_style"]' => array('checked' => TRUE),
        ),
      ),
    );
    $form['nivo_slider_image_style_thumb'] = array(
      '#type' => 'select',
      '#title' => t('Tumbnail image style'),
      '#options' => $available_image_styles,
      '#default_value' => $config->get('options.image_style_thumb'),
      '#description' => t('Select an image style to apply to slide thumbnail images.'),
      '#states' => array(
        'visible' => array(
          ':input[name="nivo_slider_image_style"]' => array('checked' => TRUE),
        ),
      ),
    );
  }

  // General
  $form['general'] = array(
    '#type' => 'details',
    '#title' => t('General'),
    '#collapsed' => TRUE,
  );
  $form['general']['nivo_slider_random_start'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use a random starting slide'),
    '#default_value' => $config->get('options.random_start'),
    '#description' => t('Randomly select a slide to begin the slideshow with.'),
  );
  $form['general']['nivo_slider_start_slide'] = array(
    '#type' => 'textfield',
    '#title' => t('Starting slide'),
    '#maxlength' => 3,
    '#size' => 3,
    '#default_value' => $config->get('options.start_slide'),
    '#description' => t('Specify which slide the slideshow begins with.'),
    '#field_prefix' => t('Slide #'),
    '#states' => array(
      'visible' => array(
        ':input[name="nivo_slider_random_start"]' => array('checked' => FALSE),
      ),
    ),
  );

  // Effects
  $form['effects'] = array(
    '#type' => 'details',
    '#title' => t('Effects'),
    '#collapsed' => TRUE,
  );
  $form['effects']['nivo_slider_effect'] = array(
    '#type' => 'select',
    '#title' => t('Effect'),
    '#options' => array(
      'sliceDown' => t('Slice Down'),
      'sliceDownLeft' => t('Slice Down Left'),
      'sliceUp' => t('Slice Up'),
      'sliceUpLeft' => t('Slice Up Left'),
      'sliceUpDown' => t('Slice Up Down'),
      'sliceUpDownLeft' => t('Slice Up Down Left'),
      'fold' => t('Fold'),
      'fade' => t('Fade'),
      'random' => t('Random'),
      'slideInRight' => t('Slide In Right'),
      'slideInLeft' => t('Slide in Left'),
      'boxRandom' => t('Box Random'),
      'boxRain' => t('Box Rain'),
      'boxRainReverse' => t('Box Rain Reverse'),
      'boxRainGrow' => t('Box Rain Grow'),
      'boxRainGrowReverse' => t('Box Rain Grow Reverse'),
    ),
    '#default_value' => $config->get('options.effect'),
    '#description' => t('Select an effect. The chosen effect will be used to transition between slides.'),
  );
  $form['effects']['effect_properties'] = array(
    '#type' => 'details',
    '#title' => t('Effect properties'),
    '#collapsed' => TRUE,
  );
  $form['effects']['effect_properties']['nivo_slider_slices'] = array(
    '#type' => 'textfield',
    '#title' => t('Slices'),
    '#maxlength' => 3,
    '#size' => 3,
    '#default_value' => $config->get('options.slices'),
    '#description' => t('Used for slice animations.'),
    '#field_suffix' => t('Slices'),
    '#states' => array(
      'visible' => array(
        array(':input[name="nivo_slider_effect"]' => array('value' => 'sliceDown')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'sliceDownLeft')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'sliceUp')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'sliceUpLeft')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'sliceUpDown')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'sliceUpDownLeft')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'fold')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'fade')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'random')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'slideInRight')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'slideInLeft')),
      ),
    ),
  );
  $form['effects']['effect_properties']['nivo_slider_box_columns'] = array(
    '#type' => 'textfield',
    '#title' => t('Box columns'),
    '#maxlength' => 3,
    '#size' => 3,
    '#default_value' => $config->get('options.box_columns'),
    '#description' => t('Used for box animations.'),
    '#field_suffix' => t('Columns'),
    '#states' => array(
      'visible' => array(
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRandom')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRain')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRainReverse')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRainGrow')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRainGrowReverse')),
      ),
    ),
  );
  $form['effects']['effect_properties']['nivo_slider_box_rows'] = array(
    '#type' => 'textfield',
    '#title' => t('Box rows'),
    '#maxlength' => 3,
    '#size' => 3,
    '#default_value' => $config->get('options.box_rows'),
    '#description' => t('Used for box animations.'),
    '#field_suffix' => t('Rows'),
    '#states' => array(
      'visible' => array(
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRandom')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRain')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRainReverse')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRainGrow')),
        array(':input[name="nivo_slider_effect"]' => array('value' => 'boxRainGrowReverse')),
      ),
    ),
  );
  $form['effects']['nivo_slider_animation_speed'] = array(
    '#type' => 'textfield',
    '#title' => t('Animation speed'),
    '#maxlength' => 6,
    '#size' => 6,
    '#default_value' => $config->get('options.animation_speed'),
    '#description' => t('Enter a time in milliseconds. The animation speed determines how long it takes to transition from one slide to the next.'),
    '#field_suffix' => t('ms'),
  );
  $form['effects']['nivo_slider_pause_on_hover'] = array(
    '#type' => 'checkbox',
    '#title' => t('Pause animation when hovering over the slide'),
    '#default_value' => $config->get('options.pause_on_hover'),
    '#description' => t('Disable slide transitions while the user is hovering over the slide.'),
  );

  // Navigation
  $form['navigation'] = array(
    '#type' => 'details',
    '#title' => t('Navigation'),
    '#collapsed' => TRUE,
  );
  $form['navigation']['nivo_slider_directional_navigation'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display directional navigation'),
    '#default_value' => $config->get('options.directional_navigation'),
    '#description' => t('Display navigation buttons to go back to the previous slide and move forward to the next slide.'),
  );
  $form['navigation']['nivo_slider_next_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Next button text'),
    '#default_value' => $config->get('options.next_text'),
    '#states' => array(
      'visible' => array(
        ':input[name="nivo_slider_directional_navigation"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['navigation']['nivo_slider_previous_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Previous button text'),
    '#default_value' => $config->get('options.previous_text'),
    '#states' => array(
      'visible' => array(
        ':input[name="nivo_slider_directional_navigation"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['navigation']['nivo_slider_control_navigation'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display control navigation'),
    '#default_value' => $config->get('options.control_navigation'),
    '#description' => t('Display navigation buttons to select an individual slide.'),
  );
  $form['navigation']['nivo_slider_control_nav_thumbs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use thumbnails for control navigation'),
    '#default_value' => $config->get('options.nav_thumbs'),
    '#description' => t('Use thumbnails of each slide as control navigation buttons. In order for thumbnails to be displayed, a compatible theme is required.'),
    '#states' => array(
      'visible' => array(
        ':input[name="nivo_slider_control_navigation"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['navigation']['nivo_slider_manual_advance'] = array(
    '#type' => 'checkbox',
    '#title' => t('Disable automatic slide transitions'),
    '#default_value' => $config->get('options.manual_advance'),
    '#description' => t('For the use to manually navigate between slides.'),
  );
  $form['navigation']['nivo_slider_pause_time'] = array(
    '#type' => 'textfield',
    '#title' => t('Pause time'),
    '#maxlength' => 6,
    '#size' => 6,
    '#default_value' => $config->get('options.pause_time'),
    '#description' => t('Enter a time in milliseconds. The pause time determines how long each slide is displayed before transitioning to the next slide.'),
    '#field_suffix' => t('ms'),
    '#states' => array(
      'visible' => array(
        ':input[name="nivo_slider_manual_advance"]' => array('checked' => FALSE),
      ),
    ),
  );

  return system_config_form($form, $form_state);
}

/**
 * Form submission handler for nivo_slider_option_configuration_form().
 */
function nivo_slider_option_configuration_form_submit($form, &$form_state) {
  config('nivo_slider.settings')
    ->set('options.theme', $form_state['values']['nivo_slider_theme'])
    ->set('options.image_style', $form_state['values']['nivo_slider_image_style'])
    ->set('options.image_style_slide', $form_state['values']['nivo_slider_image_style_slide'])
    ->set('options.image_style_thumb', $form_state['values']['nivo_slider_image_style_thumb'])
    ->set('options.random_start', $form_state['values']['nivo_slider_random_start'])
    ->set('options.start_slide', $form_state['values']['nivo_slider_start_slide'])
    ->set('options.effect', $form_state['values']['nivo_slider_effect'])
    ->set('options.slices', $form_state['values']['nivo_slider_slices'])
    ->set('options.box_columns', $form_state['values']['nivo_slider_box_columns'])
    ->set('options.box_rows', $form_state['values']['nivo_slider_box_rows'])
    ->set('options.animation_speed', $form_state['values']['nivo_slider_animation_speed'])
    ->set('options.pause_on_hover', $form_state['values']['nivo_slider_pause_on_hover'])
    ->set('options.directional_navigation', $form_state['values']['nivo_slider_directional_navigation'])
    ->set('options.next_text', $form_state['values']['nivo_slider_next_text'])
    ->set('options.previous_text', $form_state['values']['nivo_slider_previous_text'])
    ->set('options.control_navigation', $form_state['values']['nivo_slider_control_navigation'])
    ->set('options.control_nav_thumbs', $form_state['values']['nivo_slider_control_nav_thumbs'])
    ->set('options.manual_advance', $form_state['values']['nivo_slider_manual_advance'])
    ->set('options.pause_time', $form_state['values']['nivo_slider_pause_time'])
    ->save();
 }
