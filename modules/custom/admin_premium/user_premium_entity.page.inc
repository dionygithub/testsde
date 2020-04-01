<?php

/**
 * @file
 * Contains user_premium_entity.page.inc.
 *
 * Page callback for User premium entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for User premium entity templates.
 *
 * Default template: user_premium_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_user_premium_entity(array &$variables) {
  // Fetch UserPremiumEntity Entity Object.
  $user_premium_entity = $variables['elements']['#user_premium_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
