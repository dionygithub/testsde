<?php

/**
 * @file
 * Contains points_extras_entity.page.inc.
 *
 * Page callback for Points extras entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Points extras entity templates.
 *
 * Default template: points_extras_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_points_extras_entity(array &$variables) {
  // Fetch PointsExtrasEntity Entity Object.
  $points_extras_entity = $variables['elements']['#points_extras_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
