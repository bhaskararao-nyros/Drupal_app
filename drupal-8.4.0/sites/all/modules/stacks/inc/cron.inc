<?php

/**
 * @file
 * Contains cron.inc.
 */

use Drupal\stacks\Entity\WidgetInstanceEntity;

/**
 * Implements hook_cron().
 *
 * We need to delete all "ghost" required widget instances and widget entities. These
 * are entities created when loading a create node form, but never saving the form.
 */
function stacks_cron() {
  // Loop through all widget fields.
  $widget_fields = \Drupal::service('entity_field.manager')
    ->getFieldMapByFieldType('stacks_type');
  $deleted = 0;

  foreach ($widget_fields as $field_row) {
    $field_name = key($field_row);
    if (!empty($field_row[$field_name]['type']) && $field_row[$field_name]['type'] == 'stacks_type') {
      // Get all widget instances that are not attached to a node.
      $db_field_name = \Drupal::database()
        ->escapeTable($field_name . '_widget_instance_id');
      $db_table_name = \Drupal::database()->escapeTable('node__' . $field_name);
      $query = db_query('SELECT wi.id FROM {widget_instance_entity} wi WHERE wi.id NOT IN (SELECT f.' . $db_field_name . ' FROM {' . $db_table_name . '} f) AND wi.required=\'1\' AND wi.created<=:time', [
        ':time' => REQUEST_TIME - 10800, // 3 hours ago
      ]);

      // Delete each ghost entity.
      foreach ($query as $record) {
        // Get the widget instance.
        $widget_instance = WidgetInstanceEntity::load($record->id);
        $widget_entity = $widget_instance->getWidgetEntity();

        // Delete the entities.
        $widget_instance->delete();

        // Make sure the stacks entity exists.
        if (method_exists($widget_entity, 'delete')) {
          $widget_entity->delete();
        }

        $deleted++;
      }
    }

  }

  if ($deleted) {
    \Drupal::logger('stacks_cron')
      ->info('Deleted ' . $deleted . ' ghost required widget instance entities.');
  }

}

/**
 * Function for deleting all stacks and widget instances.
 */
function stacks_clear_all() {

  $deleted = 0;

  $query = db_query('SELECT wi.id FROM {widget_instance_entity} wi');

  // Delete each ghost entity.
  foreach ($query as $record) {
    // Get the widget instance.
    $widget_instance = WidgetInstanceEntity::load($record->id);
    $widget_entity = $widget_instance->getWidgetEntity();

    // Delete the entities.
    $widget_instance->delete();

    // Make sure the stacks entity exists.
    if (method_exists($widget_entity, 'delete')) {
      $widget_entity->delete();
    }

    $deleted++;
  }

  \Drupal::logger('stacks')
    ->info('Deleted ' . $deleted . ' widget instance entities.');
}
