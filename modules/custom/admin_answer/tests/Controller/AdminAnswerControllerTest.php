<?php

namespace Drupal\admin_answer\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_answer module.
 */
class AdminAnswerControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_answer AdminAnswerController's controller functionality",
      'description' => 'Test Unit for module admin_answer and controller AdminAnswerController.',
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
   * Tests admin_answer functionality.
   */
  public function testAdminAnswerController() {
    // Check that the basic functions of module admin_answer.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
