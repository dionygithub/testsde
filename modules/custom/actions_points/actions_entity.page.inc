<?php

/**
 * @file
 * Contains actions_entity.page.inc.
 *
 * Page callback for Actions entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Actions entity templates.
 *
 * Default template: actions_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_actions_entity(array &$variables) {
  // Fetch ActionsEntity Entity Object.
  $actions_entity = $variables['elements']['#actions_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
