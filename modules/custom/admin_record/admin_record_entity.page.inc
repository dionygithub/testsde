<?php

/**
 * @file
 * Contains admin_record_entity.page.inc.
 *
 * Page callback for Admin record entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Admin record entity templates.
 *
 * Default template: admin_record_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_admin_record_entity(array &$variables) {
  // Fetch AdminRecordEntity Entity Object.
  $admin_record_entity = $variables['elements']['#admin_record_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
