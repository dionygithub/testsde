<?php

/**
 * @file
 * Contains admin_test_entity.page.inc.
 *
 * Page callback for Admin test entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Admin test entity templates.
 *
 * Default template: admin_test_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_admin_test_entity(array &$variables) {
  // Fetch AdminTestEntity Entity Object.
  $admin_test_entity = $variables['elements']['#admin_test_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
