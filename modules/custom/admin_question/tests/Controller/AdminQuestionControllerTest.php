<?php

namespace Drupal\admin_question\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_question module.
 */
class AdminQuestionControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_question AdminQuestionController's controller functionality",
      'description' => 'Test Unit for module admin_question and controller AdminQuestionController.',
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
   * Tests admin_question functionality.
   */
  public function testAdminQuestionController() {
    // Check that the basic functions of module admin_question.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
