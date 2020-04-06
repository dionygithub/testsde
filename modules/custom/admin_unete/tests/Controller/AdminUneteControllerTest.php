<?php

namespace Drupal\admin_unete\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_unete module.
 */
class AdminUneteControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_unete AdminUneteController's controller functionality",
      'description' => 'Test Unit for module admin_unete and controller AdminUneteController.',
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
   * Tests admin_unete functionality.
   */
  public function testAdminUneteController() {
    // Check that the basic functions of module admin_unete.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
