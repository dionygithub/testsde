<?php

namespace Drupal\admin_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AdminTestController.
 */
class AdminTestController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hello with parameter(s): $name'),
    ];
  }

}
