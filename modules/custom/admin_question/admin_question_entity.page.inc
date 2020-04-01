<?php

/**
 * @file
 * Contains admin_question_entity.page.inc.
 *
 * Page callback for Admin question entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Admin question entity templates.
 *
 * Default template: admin_question_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_admin_question_entity(array &$variables) {
  // Fetch AdminQuestionEntity Entity Object.
  $admin_question_entity = $variables['elements']['#admin_question_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
