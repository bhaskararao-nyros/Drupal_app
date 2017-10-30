<?php
/**
 * @file
 * Contains \Drupal\stacks_content_feed\StacksQuery\StacksQueryBase
 */

namespace Drupal\stacks_content_feed\StacksQuery;

/**
 * Class StacksQueryBase
 */
abstract class StacksQueryBase {

  protected $unique_id;

  /**
   * Queries the database for nodes.
   * @param $options
   * @return mixed
   */
  abstract public function getNodeResults($options);

  /**
   * Handles the default sorting options for the database query.
   * @param $query
   * @param $order_by
   */
  protected function getNodeResultsSort(&$query, $order_by) {
    switch ($order_by) {
      case 'title_asc':
        $query->sort('title', 'ASC');
        break;

      case 'title_desc':
        $query->sort('title', 'DESC');
        break;

      case 'created_asc':
        $query->sort('created', 'ASC');
        break;

      case 'created_desc':
        $query->sort('created', 'DESC');
        break;
    }
  }

}
