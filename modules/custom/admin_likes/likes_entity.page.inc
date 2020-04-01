<?php

/**
 * @file
 * Contains likes_entity.page.inc.
 *
 * Page callback for Likes entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Likes entity templates.
 *
 * Default template: likes_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_likes_entity(array &$variables) {
  // Fetch LikesEntity Entity Object.
  $likes_entity = $variables['elements']['#likes_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
