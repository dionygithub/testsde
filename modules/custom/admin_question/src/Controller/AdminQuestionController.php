<?php

namespace Drupal\admin_question\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AdminQuestionController.
 */
class AdminQuestionController extends ControllerBase {

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
