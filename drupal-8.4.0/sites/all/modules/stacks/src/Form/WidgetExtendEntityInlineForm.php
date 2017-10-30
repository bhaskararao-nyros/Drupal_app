<?php

namespace Drupal\stacks\Form;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\inline_entity_form\InlineFormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Generic entity inline form handler.
 */
abstract class WidgetExtendEntityInlineForm implements InlineFormInterface {

  /**
   * Cleans up the form state for a submitted entity form.
   *
   * After field_attach_submit() has run and the form has been closed, the form
   * state still contains field data in $form_state->get('field'). Unless that
   * data is removed, the next form with the same #parents (reopened add form,
   * for example) will contain data (i.e. uploaded files) from the previous form.
   *
   * @param $entity_form
   *   The entity form.
   * @param $form_state
   *   The form state of the parent form.
   */
  public static function submitCleanFormState(&$entity_form, FormStateInterface $form_state) {
    $ief = $form_state->get('inline_entity_form');

    $i = 0;
    foreach ($ief as $id => $el) {
      $i++;

      if ($i == 2) {
        unset($ief[$id]);
        $form_state->set('inline_entity_form', $ief);
      }
    }
  }

}
