<?php

/**
 * @file
 * Contains theme.inc.
 */

use Drupal\stacks\Widget\WidgetTemplates;

/**
 * Implements hook_theme().
 */
function stacks_theme($existing, $type, $theme, $path) {
  $theme_array = [];

  $theme_array['widget_entity'] = [
    'render element' => 'elements',
    'file' => 'template_preprocess/widget_entity.page.inc',
    'template' => 'widget_entity',
  ];

  $theme_array['widget_entity_content_add_list'] = [
    'render element' => 'content',
    'variables' => ['content' => NULL],
    'file' => 'template_preprocess/widget_entity.page.inc',
  ];

  $theme_array['widget_instance_entity'] = [
    'render element' => 'elements',
    'file' => 'template_preprocess/widget_instance_entity.page.inc',
    'template' => 'widget_instance_entity',
  ];

  $theme_array['widget_extend'] = [
    'render element' => 'elements',
    'file' => 'template_preprocess/widget_extend.page.inc',
    'template' => 'widget_extend',
  ];

  $theme_array['widget_extend_content_add_list'] = [
    'render element' => 'content',
    'variables' => ['content' => NULL],
    'file' => 'template_preprocess/widget_extend.page.inc',
  ];

  $theme_array['stacks_admin_theme'] = [
    'render element' => 'content',
    'variables' => [
      'stacks_content_list_exist' => NULL,
    ],
  ];

  // Define all widget templates.
  $get_templates = WidgetTemplates::getAllTemplates();
  foreach ($get_templates as $template) {
    if (!isset($template['variation_machine_name'])) {
      // This is not a valid file.
      continue;
    }

    $ajax_key = '';
    if(substr($template['path_to_dir'], -4) === 'ajax') {
      $ajax_key = 'ajax_';
    }

    $theme_array_key = $ajax_key . $template['bundle'] . '__' . $template['variation_machine_name'];
    $theme_array[$theme_array_key] = [
      'template' => $template['twig_filename'],
      'path' => $template['path_to_dir'],
      'variables' => [

        // Only send the basic information for the entity.
        // Do not send full entity object (uses too much memory).
        'content_entity' => [
          'entity_id' => NULL,
          'entity_type' => NULL,
          'entity_bundle' => NULL,
        ],

        'widget_entity' => [
          'entity_id' => NULL,
          'entity_type' => NULL,
          'entity_bundle' => NULL,
        ],

        // Contains all of the field data that is meant to be
        // used in the templates.
        'fields' => NULL,
        'rendered_fields' => NULL,

        // Wrapper variables.
        'wrapper_id' => NULL,
        'wrapper_classes' => NULL,
        'template_theme' => NULL,

        // For grid widgets, holds necessary info, including ajax variables.
        'grid' => [
          'results' => NULL,
          'filters' => NULL,
          'ajax_attributes' => NULL,
        ],
      ],
    ];
  }

  return $theme_array;

}
