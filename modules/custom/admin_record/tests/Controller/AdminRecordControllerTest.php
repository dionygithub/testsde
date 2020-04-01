<?php

namespace Drupal\admin_record\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_record module.
 */
class AdminRecordControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_record AdminRecordController's controller functionality",
      'description' => 'Test Unit for module admin_record and controller AdminRecordController.',
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
   * Tests admin_record functionality.
   */
  public function testAdminRecordController() {
    // Check that the basic functions of module admin_record.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
