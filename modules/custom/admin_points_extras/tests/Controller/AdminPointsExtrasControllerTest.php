<?php

namespace Drupal\admin_points_extras\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_points_extras module.
 */
class AdminPointsExtrasControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_points_extras AdminPointsExtrasController's controller functionality",
      'description' => 'Test Unit for module admin_points_extras and controller AdminPointsExtrasController.',
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
   * Tests admin_points_extras functionality.
   */
  public function testAdminPointsExtrasController() {
    // Check that the basic functions of module admin_points_extras.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
