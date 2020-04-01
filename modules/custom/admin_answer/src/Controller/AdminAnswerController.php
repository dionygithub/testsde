<?php

namespace Drupal\admin_answer\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AdminAnswerController.
 */
class AdminAnswerController extends ControllerBase {

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
