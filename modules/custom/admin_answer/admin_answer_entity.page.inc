<?php

/**
 * @file
 * Contains admin_answer_entity.page.inc.
 *
 * Page callback for Admin answer entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Admin answer entity templates.
 *
 * Default template: admin_answer_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_admin_answer_entity(array &$variables) {
  // Fetch AdminAnswerEntity Entity Object.
  $admin_answer_entity = $variables['elements']['#admin_answer_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
