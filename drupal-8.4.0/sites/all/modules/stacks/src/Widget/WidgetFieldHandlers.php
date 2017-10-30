<?php
/**
 * @file
 * Contains \Drupal\stacks\Widget\WidgetFieldHandlers
 */

namespace Drupal\stacks\Widget;

use Drupal\Core;
use Drupal\stacks\Widget\WidgetData;

/**
 * Class WidgetFieldHandlers
 */
class WidgetFieldHandlers {

  private $field_type;
  private $field;
  private $value;
  private $delta;

  /**
   * Returns the render array based on field type.
   *
   * @param $field_type
   * @param $field
   * @param $value
   * @param $delta
   */
  function __construct($field_type, $field, $value, $delta) {
    // Set the values
    $this->field_type = $field_type;
    $this->field = $field;
    $this->value = $value;
    $this->delta = $delta;
  }

  /**
   * Returns render array based on field type.
   */
  public function getRenderArray() {
    $method = 'field_' . $this->field_type;
    if (!method_exists($this, $method)) {
      // Debug,
      $field_name = $this->field->getName();
      drupal_set_message(t("Missing class handler for field @method. Field name: @fieldname", [
        '@method' => $method,
        '@fieldname' => $field_name,
      ]), 'warning');

      $method = 'string';
    }

    return $this->$method();
  }

  /**
   * Returns the value of the string.
   *
   * This returns a simple #markup render array, when there is a 'value'.
   * {{ fields.field_name }}
   *
   * You do not have to access the "markup" key. Don't access this key or it won't
   * print out the html correctly.
   */
  private function string() {
    return [
      '#markup' => isset($this->value['value']) ? $this->value['value'] : '',
    ];
  }

  /**
   * Returns a number value.
   *
   * This returns the value under the "num" key in the render array.
   * {{ fields.field_name.num }}
   */
  private function num() {
    return [
      'num' => isset($this->value['value']) ? $this->value['value'] : '',
    ];
  }

  /**
   * Returns an image.
   *
   * Returns image.url and image.alt. To output a specific image style, send
   * this array to a custom twig extension.
   * {{ fields.image_field|image('large', 'test_class') }}
   *
   * Use {{ fields.image_field.url }} to access the original url of the image.
   */
  private function field_image() {
    if (!$this->field->entity) {
      return [];
    }

    $uri = $this->field->entity->getFileUri();

    return [
      'url' => file_create_url($uri),
      'title' => isset($this->field->title) ? $this->field->title : '',
      'alt' => isset($this->field->alt) ? $this->field->alt : '',
      'uri' => $uri,
    ];
  }

  /**
   * Returns an embed video values.
   *
   * Returns video.url, video.id (YouTube), video.player
   *
   * {{ fields.field_test_video.url }}
   * {{ fields.field_test_video.id }}
   * {{ fields.field_test_video.player }}
   */
  private function field_video_embed_field() {
    // @TODO Make this more flexible
    if (!isset($this->value['value'])) {
      return [];
    }

    $id = explode('?v=', $this->value['value']);
    $player = '';

    if (count($id) == 1) {
      $id = 0;
    }
    else {
      $id = $id[1];
      $player = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $id . '?rel=0&enablejsapi=1" frameborder="0" allowfullscreen></iframe>';
    }

    return [
      'url' => $this->value['value'],
      'id' => $id,
      'player' => t($player),
    ];
  }

  /**
   * Returns a file.
   *
   * Returns URL of the file.
   * {{ fields.field_test_file.url }}
   */
  private function field_file() {
    if (!$this->field->entity) {
      return [];
    }

    $uri = $this->field->entity->getFileUri();

    return [
      'url' => file_create_url($uri),
      'description' => (isset($this->value['description']) ? $this->value['description'] : ''),
      'uri' => $uri,
    ];
  }


  /**
   * Returns a link value.
   *
   * Returns link.title and link.url. Also returns link.html with the render
   * array for the full link html.
   *
   * {{ fields.field_test_link.title }}
   * {{ fields.field_test_link.url }}
   * {{ fields.field_test_link.html }}
   */
  private function field_link() {
    $uri = $this->value['uri'];

    if (empty($uri)) {
      return [];
    }

    $url_object = Core\Url::fromUri($uri);
    if (strpos($uri, 0, 7) != 'http://' && strpos($uri, 0, 8) != 'https://') {
      $url = $url_object->toString();
    }
    else {
      $url = $uri;
    }

    return [
      'title' => $this->value['title'],
      'url' => $url,
      'html' => Core\Link::fromTextAndUrl($this->value['title'], $url_object)
        ->toRenderable(),
    ];
  }

  /**
   * Returns a datetime value.
   *
   * Returns the value of the string. Use it like this:
   * {{ fields.field_test_date.markup|date("m/d/Y") }}
   */
  private function field_datetime() {
    return [
      'date' => isset($this->value['value']) ? $this->value['value'] : '',
    ];
  }

  /**
   * Returns a boolean value (true or false).
   *
   * {% if fields.field_test_boolean.checked %}
   * {% endif %}
   */
  private function field_boolean() {
    return [
      'checked' => ($this->value['value'] == "1") ? TRUE : FALSE,
    ];
  }

  /**
   * Returns an entity reference.
   *
   * Output a reference field that only allows one value:
   * {{ fields.field_test_content.entity|view_mode('teaser') }}
   *
   * If your content reference field has multiple values, loop through it like
   * normal:
   * {% for row in fields.field_test_content %}
   *   {{ row.entity|view_mode('teaser') }}
   * {% endfor %}
   */
  private function field_entity_reference() {
    $entity = $this->field->referencedEntities()[$this->delta];

    // Is this a contact form entity reference?
    if ($entity->getEntityTypeId() == 'contact_form') {

      // Add the form as a variable to the entity reference.
      // This allows the form to be printed like this: {{ fields.field_select_contact_form.form }}
      $message = \Drupal::entityTypeManager()
        ->getStorage('contact_message')
        ->create([
          'contact_form' => $entity->id(),
        ]);
      $form = \Drupal::service('entity.form_builder')->getForm($message);

      return ['form' => $form];
    }

    if (!method_exists($entity, 'getFields')) {
      return [];
    }

    // Return all of the fields for this entity.
    $get_field_data = new WidgetData();
    $data = ['entity_id' => $entity->id()];

    if (method_exists($entity, 'getType')) {
      $data['bundle'] = $entity->getType();
    }

    $data['entity_type'] = $entity->getEntityTypeId();

    $field_exceptions = WidgetData::fieldExceptionsDefault();
    foreach ($entity->getFields() as $key => &$field) {
      // Don't include the fields/properties we should ignore by default.
      if (in_array($key, $field_exceptions)) {
        continue;
      }

      $get_field_data->fieldValues($key, $field, $data);
    }

    return $data;
  }

  /**
   * These methods all use $this->string().
   */
  private function field_string() {
    return $this->string();
  }

  private function field_list_string() {
    return $this->string();
  }

  private function field_text() {
    return $this->string();
  }

  private function field_text_long() {
    return $this->string();
  }

  private function field_text_with_summary() {
    return $this->string();
  }

  private function field_string_long() {
    return $this->string();
  }

  private function field_email() {
    return $this->string();
  }

  /**
   * These methods all use $this->num().
   */
  private function field_list_integer() {
    return $this->num();
  }

  private function field_list_float() {
    return $this->num();
  }

  private function field_integer() {
    return $this->num();
  }

  private function field_decimal() {
    return $this->num();
  }

  private function field_float() {
    return $this->num();
  }

}
