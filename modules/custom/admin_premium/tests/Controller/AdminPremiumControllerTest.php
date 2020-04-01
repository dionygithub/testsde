<?php

namespace Drupal\admin_premium\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_premium module.
 */
class AdminPremiumControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_premium AdminPremiumController's controller functionality",
      'description' => 'Test Unit for module admin_premium and controller AdminPremiumController.',
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
   * Tests admin_premium functionality.
   */
  public function testAdminPremiumController() {
    // Check that the basic functions of module admin_premium.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
