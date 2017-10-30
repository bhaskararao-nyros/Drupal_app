<?php

/**
 * @file
 * Theme and preprocess functions for Nivo Slider.
 */

/**
 * Implements template_preprocess_hook().
 */
function template_preprocess_nivo_slider_wrapper(&$variables) {
  $config = config('nivo_slider.settings');
  $variables['theme'] = $config->get('options.theme');
  $variables['banners'] = nivo_slider_slider();
  $variables['html_captions'] = $config->get('html_captions');
}
