<?php

/**
 * @file
 * Contains premium_entity.page.inc.
 *
 * Page callback for Premium entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Premium entity templates.
 *
 * Default template: premium_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_premium_entity(array &$variables) {
  // Fetch PremiumEntity Entity Object.
  $premium_entity = $variables['elements']['#premium_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
