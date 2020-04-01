<?php

namespace Drupal\admin_tests\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_tests module.
 */
class AdminTestsControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_tests AdminTestsController's controller functionality",
      'description' => 'Test Unit for module admin_tests and controller AdminTestsController.',
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
   * Tests admin_tests functionality.
   */
  public function testAdminTestsController() {
    // Check that the basic functions of module admin_tests.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
