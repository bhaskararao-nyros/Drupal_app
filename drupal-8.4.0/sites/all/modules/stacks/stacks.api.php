<?php
/**
 * @file
 * Documentation for the hooks provided by this module.
 */

/**
 * Modify the render array value before it is output to the screen. This is
 * only meant for none custom widget types. For custom widget types, create the
 * method modifyRenderArray() in the grid class.
 *
 * @param $render_array
 * @param $entity ($node)
 * @param $widget_entity
 */
function hook_stacks_output_alter(&$render_array, $entity, $widget_entity) {}

/**
 * Modify the query that returns the node results.
 *
 * @param $query: The query object that you can modify.
 * @param $group: The group condition for the filters.
 * @param $context: Contains 'widget_bundle' (the bundle of the widget and
 * 'options', which are the options that are sent to the query builder function.
 */
function hook_widget_node_results_alter(&$query, $group, &$context) {}
