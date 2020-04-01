<?php

namespace Drupal\actions_points\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the actions_points module.
 */
class ActionsPointsControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "actions_points ActionsPointsController's controller functionality",
      'description' => 'Test Unit for module actions_points and controller ActionsPointsController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests actions_points functionality.
   */
  public function testActionsPointsController() {
    // Check that the basic functions of module actions_points.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
