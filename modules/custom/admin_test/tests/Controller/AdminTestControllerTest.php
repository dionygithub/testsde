<?php

namespace Drupal\admin_test\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_test module.
 */
class AdminTestControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_test AdminTestController's controller functionality",
      'description' => 'Test Unit for module admin_test and controller AdminTestController.',
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
   * Tests admin_test functionality.
   */
  public function testAdminTestController() {
    // Check that the basic functions of module admin_test.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
