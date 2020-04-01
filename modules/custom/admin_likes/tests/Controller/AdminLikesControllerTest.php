<?php

namespace Drupal\admin_likes\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the admin_likes module.
 */
class AdminLikesControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "admin_likes AdminLikesController's controller functionality",
      'description' => 'Test Unit for module admin_likes and controller AdminLikesController.',
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
   * Tests admin_likes functionality.
   */
  public function testAdminLikesController() {
    // Check that the basic functions of module admin_likes.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
