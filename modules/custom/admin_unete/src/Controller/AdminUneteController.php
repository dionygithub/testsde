<?php

namespace Drupal\admin_unete\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AdminUneteController.
 */
class AdminUneteController extends ControllerBase {

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


  public function page_unete(){

    global $base_url;

    $logged_in = \Drupal::currentUser()->isAuthenticated();

    if($logged_in){
      return $this->redirect('user.page');
    }

    $build['page-unete'] = array(
        '#theme' => 'page_unete',
        '#data' => array('urlRegistroUser' => $base_url.'/user/unete/registro'),
    );

    return $build;
  }


  public function registrarUser(){

    $datos = \Drupal::request()->request->all();

    //echo'<pre>'; print_r($datos); die;

  }

}
